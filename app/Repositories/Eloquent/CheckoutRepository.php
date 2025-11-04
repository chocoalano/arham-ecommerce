<?php

namespace App\Repositories\Eloquent;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipment;
use App\Models\ShippingQuote;
use App\Models\Payment;
use App\Models\PaymentLog;
use App\Repositories\Contracts\CheckoutRepositoryInterface;
use App\Services\MidtransService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutRepository implements CheckoutRepositoryInterface
{
    public function prepare(int $customerId): array
    {
        $cart = Cart::with(['items.purchasable' => function ($morph) {
            $morph->morphWith([
                Product::class => [],
                ProductVariant::class => ['product'],
            ]);
        }])->where('customer_id', $customerId)->first();

        $items = collect();
        $subtotal = 0;

        if ($cart) {
            $items = $cart->items->map(fn($ci) => $this->mapCartItem($ci));
            $subtotal = (float) $cart->subtotal();
        }

        $address = Address::where('customer_id', $customerId)
            ->orderByDesc('id')
            ->first();

        return [
            'items'         => $items->values()->all(),
            'subtotal'      => $subtotal,
            'address'       => $address ? $address->only([
                'recipient_name','email','phone',
                'address_line1','address_line2','province_id','city_id','postal_code'
            ]) : null,
            'payment_methods' => [
                ['code' => 'manual_transfer', 'label' => 'Transfer Bank (Manual)'],
                ['code' => 'cod',             'label' => 'Bayar di Tempat (COD)'],
            ],
        ];
    }

    public function getShippingQuotes(array $addressData): array
{
    // Model ShippingQuote: cart_id | address_id | courier | service | cost | etd | rajaongkir_response
    $addressId = $addressData['address_id'] ?? null;
    $cartId    = $addressData['cart_id']    ?? null;
    $courier   = $addressData['courier']    ?? null;

    $q = ShippingQuote::query();

    // Filter paling relevan sesuai struktur tabel
    if ($addressId) {
        $q->where('address_id', $addressId);
    }
    if ($cartId) {
        $q->where('cart_id', $cartId);
    }
    if ($courier) {
        $q->where('courier', $courier);
    }

    // Urutkan berdasarkan biaya terendah (lebih masuk akal untuk checkout)
    $rows = $q->orderBy('cost')->limit(10)->get();

    // Fallback flat-rate jika belum ada quote yang tersimpan
    if ($rows->isEmpty()) {
        return [
            [
                'code'     => 'JNE_REG',
                'label'    => 'JNE Reguler (2-4 hari)',
                'cost'     => 25000,
                'etd_days' => '2-4',
                'carrier'  => 'JNE',
            ],
            [
                'code'     => 'SICEPAT_REG',
                'label'    => 'SiCepat Reguler (2-3 hari)',
                'cost'     => 23000,
                'etd_days' => '2-3',
                'carrier'  => 'SiCepat',
            ],
            [
                'code'     => 'JNT_EXP',
                'label'    => 'J&T Express (1-2 hari)',
                'cost'     => 28000,
                'etd_days' => '1-2',
                'carrier'  => 'J&T',
            ],
        ];
    }

    return $rows->map(function (ShippingQuote $r) {
        // Buat code yang stabil dari courier + service
        $code = strtoupper(preg_replace('/\s+/', '', (string) $r->courier))
              . '_' .
                strtoupper(preg_replace('/\s+/', '', (string) $r->service));

        return [
            'code'     => $code,
            'label'    => trim(($r->courier ?? 'Kurir') . ' ' . ($r->service ?? '')),
            'cost'     => (float) $r->cost,
            'etd_days' => $this->normalizeEtd($r->etd), // ex: "2-4", "1-2"
            'carrier'  => $r->courier ?? null,
            // Jika perlu debugging/insight, Anda bisa expose raw data RO:
            // 'raw'   => $r->rajaongkir_response,
        ];
    })->values()->all();
}

    public function placeOrder(
        int $customerId,
        array $addressData,
        string $shippingCode,
        string $paymentMethod,
        ?string $orderNote = null
    ): array {
        return DB::transaction(function () use ($customerId, $addressData, $shippingCode, $paymentMethod, $orderNote) {

            $cart = Cart::with(['items.purchasable' => function ($morph) {
                $morph->morphWith([
                    Product::class => [],
                    ProductVariant::class => ['product'],
                ]);
            }])->where('customer_id', $customerId)->lockForUpdate()->first();

            if (!$cart || $cart->items->isEmpty()) {
                throw new \RuntimeException('Keranjang kosong.');
            }

            $subtotal = (float) $cart->subtotal();
            if ($subtotal <= 0) {
                throw new \RuntimeException('Subtotal tidak valid.');
            }

            // Address: create/update (jadikan default bila belum ada)
            $address = Address::updateOrCreate(
                [
                    'customer_id' => $customerId,
                    // kriteria unik minimal (opsional): by postal + line1
                    'postal_code' => $addressData['postal_code'] ?? null,
                    'address_line1' => $addressData['address_line1'] ?? null,
                ],
                [
                    'recipient_name' => $addressData['recipient_name'] ?? '',
                    'email'          => $addressData['email'] ?? '',
                    'phone'          => $addressData['phone'] ?? '',
                    'address_line2'  => $addressData['address_line2'] ?? null,
                    'province_id'    => $addressData['province_id'] ?? null,
                    'city_id'        => $addressData['city_id'] ?? null,
                    'is_default'     => true,
                ]
            );

            // Shipping cost dari kode yang dipilih (validasi terhadap quotes server)
            $quotes = $this->getShippingQuotes($addressData);
            $chosen = collect($quotes)->firstWhere('code', $shippingCode);
            if (!$chosen) {
                throw new \RuntimeException('Metode pengiriman tidak valid.');
            }
            $shippingCost = (float) $chosen['cost'];

            // Hitung total
            $total = $subtotal + $shippingCost;

            // Buat Order
            $orderNumber = $this->generateOrderNumber();
            $order = new Order();
            $order->customer_id   = $customerId;
            $order->order_number  = $orderNumber;
            $order->currency      = 'IDR';
            $order->status        = 'pending'; // awaiting_payment
            $order->subtotal      = $subtotal;
            $order->shipping_cost = $shippingCost;
            $order->discount_total= 0;
            $order->tax_total     = 0;
            $order->grand_total   = $total;
            $order->notes          = $orderNote;
            // snapshot alamat kirim
            $order->customer_name    = $address->recipient_name;
            $order->customer_email   = $address->email;
            $order->customer_phone   = $address->phone;
            $order->billing_address_snapshot = $address->address_line1;
            $order->shipping_address_snapshot= $address->address_line2;
            $order->save();

            // Order Items
            foreach ($cart->items as $ci) {
                // Validasi stok sederhana (opsional)
                if ($ci->purchasable_type === ProductVariant::class && isset($ci->purchasable->stock_quantity)) {
                    if ($ci->purchasable->stock_quantity < $ci->quantity) {
                        throw new \RuntimeException('Stok varian tidak mencukupi: ' . ($ci->purchasable->name ?? ''));
                    }
                }
                if ($ci->purchasable_type === Product::class && isset($ci->purchasable->stock_quantity)) {
                    if ($ci->purchasable->stock_quantity < $ci->quantity) {
                        throw new \RuntimeException('Stok produk tidak mencukupi: ' . ($ci->purchasable->name ?? ''));
                    }
                }

                OrderItem::create([
                    'order_id'        => $order->id,
                    'purchasable_type'=> $ci->purchasable_type,
                    'purchasable_id'  => $ci->purchasable_id,
                    'sku'             => $ci->sku,
                    'name'            => $ci->name,
                    'quantity'        => $ci->quantity,
                    'price'           => $ci->price,
                    'subtotal'        => $ci->price * $ci->quantity,
                    'weight_gram'     => $ci->weight_gram ?? 0,
                ]);
            }

            // Shipment
            $shipment = new Shipment();
            $shipment->order_id      = $order->id;
            $shipment->courier  = $chosen['code'];
            $shipment->service = $chosen['label'];
            $shipment->cost          = $shippingCost;
            $shipment->status        = 'pending';
            $shipment->save();

            // Payment
            $payment = new Payment();
            $payment->order_id      = $order->id;
            $payment->customer_id   = $customerId;
            $payment->gross_amount  = $total;
            $payment->currency      = 'IDR';
            $payment->transaction_status = 'pending';
            $payment->save();

            PaymentLog::create([
                'payment_id' => $payment->id,
                'status'     => 'pending',
                'message'    => 'Payment created',
                'payload'    => ['method' => $paymentMethod],
            ]);

            // Generate Midtrans Snap Token untuk online payment
            $snapToken = null;
            if ($paymentMethod !== 'cod' && $paymentMethod !== 'manual_transfer') {
                try {
                    $midtransService = new MidtransService();
                    $snapToken = $midtransService->createSnapToken($order, $payment);
                } catch (\Exception $e) {
                    // Log error tapi jangan fail transaksi
                    \Log::error('Failed to create Midtrans snap token: ' . $e->getMessage());
                }
            }

            // Kosongkan cart
            CartItem::where('cart_id', $cart->id)->delete();

            return [
                'success'       => true,
                'order_id'      => $order->id,
                'order_number'  => $order->order_number,
                'payment_id'    => $payment->id,
                'snap_token'    => $snapToken,
                'payment_method'=> $paymentMethod,
                'redirect_url'  => route('checkout.thankyou', $order->order_number),
                'message'       => 'Pesanan berhasil dibuat. Silakan lanjutkan pembayaran.',
            ];
        });
    }

    /* ================= Helpers ================= */

    protected function mapCartItem(CartItem $ci): array
    {
        $type = $ci->purchasable_type;
        $name = $ci->name ?: '';
        $img  = null;
        $url  = '#';

        if ($type === Product::class) {
            /** @var Product $p */
            $p = $ci->purchasable;
            if ($p) {
                $name = $p->name;
                $url  = url('/product/'.$p->slug);
                $img  = method_exists($p, 'firstImageUrl') ? $p->firstImageUrl() : ($p->image_path ?? null);
            }
        } elseif ($type === ProductVariant::class) {
            /** @var ProductVariant $v */
            $v = $ci->purchasable;
            if ($v) {
                $name = ($v->product->name ?? '') . ' - ' . ($v->name ?? '');
                $url  = url('/product/'.$v->product->slug);
                $img  = method_exists($v->product, 'firstImageUrl') ? $v->product->firstImageUrl() : ($v->product->image_path ?? null);
            }
        }

        return [
            'id'          => $ci->id,
            'sku'         => $ci->sku,
            'name'        => $name,
            'image'       => $img ?? asset('images/placeholder.jpg'),
            'url'         => $url,
            'quantity'    => (int) $ci->quantity,
            'price'       => (float) $ci->price,
            'subtotal'    => (float) $ci->subtotal,
            'formatted_price'    => 'Rp ' . number_format($ci->price, 0, ',', '.'),
            'formatted_subtotal' => 'Rp ' . number_format($ci->subtotal, 0, ',', '.'),
            'purchasable_type'   => $type,
            'purchasable_id'     => (int) $ci->purchasable_id,
        ];
    }

    protected function generateOrderNumber(): string
    {
        return 'INV'.now()->format('YmdHis').Str::upper(Str::random(4));
    }

    protected function normalizeEtd(?string $etd): string
    {
        if (!$etd) {
            return '';
        }

        // Remove common suffix patterns like "HARI", "hari", "days", etc.
        $normalized = preg_replace('/\s*(hari|days?)\s*/i', '', $etd);

        // Extract numbers and ranges (e.g., "2-4", "1-2", "3")
        if (preg_match('/(\d+)\s*-\s*(\d+)/', $normalized, $matches)) {
            return $matches[1] . '-' . $matches[2];
        }

        if (preg_match('/(\d+)/', $normalized, $matches)) {
            return $matches[1];
        }

        return $etd;
    }
}
