<?php

namespace App\Repositories\Eloquent;

use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentLog;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShippingQuote;
use App\Repositories\Contracts\CheckoutRepositoryInterface;
use App\Services\MidtransService;
use App\Services\RajaOngkirService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutRepository implements CheckoutRepositoryInterface
{
    public function __construct(
        protected RajaOngkirService $rajaOngkir
    ) {}

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
            $items = $cart->items->map(fn ($ci) => $this->mapCartItem($ci));
            $subtotal = (float) $cart->subtotal();
        }

        $address = Address::where('customer_id', '=', $customerId)
            ->orderByDesc('id')
            ->first();

        return [
            'items' => $items->values()->all(),
            'subtotal' => $subtotal,
            'address' => $address ? $address->only([
                'recipient_name', 'email', 'phone',
                'address_line1', 'address_line2', 'province_id', 'city_id', 'postal_code',
            ]) : null,
            'payment_methods' => [
                ['code' => 'manual_transfer', 'label' => 'Transfer Bank (Manual)'],
                ['code' => 'cod',             'label' => 'Bayar di Tempat (COD)'],
            ],
        ];
    }

    public function getShippingQuotes(array $addressData): array
    {
        // Validasi data yang diperlukan
        if (empty($addressData['city_id'])) {
            Log::warning('RajaOngkir: city_id tidak tersedia, menggunakan fallback quotes');

            return $this->getFallbackQuotes();
        }

        $customerId = auth('customer')->id();
        if (! $customerId) {
            return $this->getFallbackQuotes();
        }

        // Ambil cart untuk menghitung total berat
        $cart = Cart::with(['items.purchasable'])->where('customer_id', $customerId)->first();

        if (! $cart || $cart->items->isEmpty()) {
            return $this->getFallbackQuotes();
        }

        // Hitung total berat dari cart items (dalam gram)
        $totalWeight = $cart->items->sum(function ($item) {
            $weight = 0;

            if ($item->purchasable_type === Product::class && $item->purchasable) {
                $weight = $item->purchasable->weight_gram ?? 0;
            } elseif ($item->purchasable_type === ProductVariant::class && $item->purchasable) {
                $weight = $item->purchasable->weight_gram ?? $item->purchasable->product->weight_gram ?? 0;
            }

            return $weight * $item->quantity;
        });

        // Minimal weight 100 gram untuk RajaOngkir
        $totalWeight = max(100, $totalWeight);

        // Get origin dari config
        $origin = config('rajaongkir.origin_city', 153); // Default: Jakarta Pusat
        $destination = (int) $addressData['city_id'];

        // Get couriers dari config atau default
        $couriers = config('rajaongkir.default_couriers', ['jne', 'pos', 'tiki']);

        try {
            // Panggil RajaOngkir API untuk multiple couriers
            $result = $this->rajaOngkir->getMultipleCosts(
                $origin,
                $destination,
                $totalWeight,
                $couriers
            );

            if (! $result['success'] || empty($result['data'])) {
                Log::warning('RajaOngkir API failed or empty response', $result);

                return $this->getFallbackQuotes();
            }

            // Parse hasil dari RajaOngkir
            $quotes = [];

            foreach ($result['data'] as $courier) {
                $courierCode = strtoupper($courier['code'] ?? '');
                $courierName = $courier['name'] ?? '';

                if (empty($courier['costs'])) {
                    continue;
                }

                foreach ($courier['costs'] as $cost) {
                    $service = $cost['service'] ?? '';
                    $description = $cost['description'] ?? '';
                    $costValue = $cost['cost'][0]['value'] ?? 0;
                    $etd = $cost['cost'][0]['etd'] ?? '';

                    if ($costValue <= 0) {
                        continue;
                    }

                    $code = $courierCode.'_'.$service;

                    $quotes[] = [
                        'code' => $code,
                        'label' => $courierName.' - '.$description.' ('.$this->normalizeEtd($etd).' hari)',
                        'cost' => (float) $costValue,
                        'etd_days' => $this->normalizeEtd($etd),
                        'carrier' => $courierName,
                        'service' => $service,
                    ];

                    // Simpan ke database untuk cache
                    ShippingQuote::updateOrCreate(
                        [
                            'cart_id' => $cart->id,
                            'courier' => $courierCode,
                            'service' => $service,
                        ],
                        [
                            'cost' => $costValue,
                            'etd' => $etd,
                            'rajaongkir_response' => json_encode($cost),
                        ]
                    );
                }
            }

            if (empty($quotes)) {
                Log::warning('No valid shipping quotes from RajaOngkir');

                return $this->getFallbackQuotes();
            }

            // Urutkan berdasarkan harga termurah
            usort($quotes, fn ($a, $b) => $a['cost'] <=> $b['cost']);

            return $quotes;

        } catch (\Exception $e) {
            Log::error('RajaOngkir Exception in getShippingQuotes', [
                'error' => $e->getMessage(),
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $totalWeight,
            ]);

            return $this->getFallbackQuotes();
        }
    }

    /**
     * Fallback quotes jika RajaOngkir tidak tersedia
     */
    protected function getFallbackQuotes(): array
    {
        return [
            [
                'code' => 'JNE_REG',
                'label' => 'JNE Reguler (2-4 hari)',
                'cost' => 25000,
                'etd_days' => '2-4',
                'carrier' => 'JNE',
                'service' => 'REG',
            ],
            [
                'code' => 'POS_REG',
                'label' => 'POS Indonesia Reguler (3-5 hari)',
                'cost' => 20000,
                'etd_days' => '3-5',
                'carrier' => 'POS',
                'service' => 'REG',
            ],
            [
                'code' => 'TIKI_REG',
                'label' => 'TIKI Reguler (2-3 hari)',
                'cost' => 23000,
                'etd_days' => '2-3',
                'carrier' => 'TIKI',
                'service' => 'REG',
            ],
        ];
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

            if (! $cart || $cart->items->isEmpty()) {
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
                    'email' => $addressData['email'] ?? '',
                    'phone' => $addressData['phone'] ?? '',
                    'address_line2' => $addressData['address_line2'] ?? null,
                    'province_id' => $addressData['province_id'] ?? null,
                    'city_id' => $addressData['city_id'] ?? null,
                    'is_default' => true,
                ]
            );

            // Shipping cost dari kode yang dipilih (validasi terhadap quotes server)
            $quotes = $this->getShippingQuotes($addressData);
            $chosen = collect($quotes)->firstWhere('code', $shippingCode);
            if (! $chosen) {
                throw new \RuntimeException('Metode pengiriman tidak valid.');
            }
            $shippingCost = (float) $chosen['cost'];

            // Hitung total
            $total = $subtotal + $shippingCost;

            // Extract courier and service from shipping code
            $codeParts = explode('_', $shippingCode, 2);
            $courier = $codeParts[0] ?? $shippingCode;
            $service = $codeParts[1] ?? ($chosen['service'] ?? '');
            $etd = $chosen['etd_days'] ?? '';

            // Calculate total weight
            $totalWeight = $cart->items->sum(function ($item) {
                $weight = 0;
                if ($item->purchasable_type === Product::class && $item->purchasable) {
                    $weight = $item->purchasable->weight_gram ?? 0;
                } elseif ($item->purchasable_type === ProductVariant::class && $item->purchasable) {
                    $weight = $item->purchasable->weight_gram ?? $item->purchasable->product->weight_gram ?? 0;
                }

                return $weight * $item->quantity;
            });

            // Buat Order
            $orderNumber = $this->generateOrderNumber();
            $order = new Order;
            $order->customer_id = $customerId;
            $order->order_number = $orderNumber;
            $order->currency = 'IDR';
            $order->status = 'pending'; // will be updated by payment gateway
            // @phpstan-ignore assign.propertyType
            $order->subtotal = $subtotal;
            // @phpstan-ignore assign.propertyType
            $order->shipping_cost = $shippingCost;
            // @phpstan-ignore assign.propertyType
            $order->shipping_total = $shippingCost;
            // @phpstan-ignore assign.propertyType
            $order->discount_total = 0;
            // @phpstan-ignore assign.propertyType
            $order->tax_total = 0;
            // @phpstan-ignore assign.propertyType
            $order->grand_total = $total;
            $order->notes = $orderNote;

            // Customer information
            $order->customer_name = $address->recipient_name;
            $order->customer_email = $address->email;
            $order->customer_phone = $address->phone;

            // Address references (link to address table)
            $order->billing_address_id = $address->id;
            $order->shipping_address_id = $address->id;

            // Address snapshots (for archival purposes)
            $order->billing_address_snapshot = json_encode([
                'recipient_name' => $address->recipient_name,
                'email' => $address->email,
                'phone' => $address->phone,
                'address_line1' => $address->address_line1,
                'address_line2' => $address->address_line2,
                'province_id' => $address->province_id,
                'city_id' => $address->city_id,
                'postal_code' => $address->postal_code,
            ]);
            $order->shipping_address_snapshot = json_encode([
                'recipient_name' => $address->recipient_name,
                'email' => $address->email,
                'phone' => $address->phone,
                'address_line1' => $address->address_line1,
                'address_line2' => $address->address_line2,
                'province_id' => $address->province_id,
                'city_id' => $address->city_id,
                'postal_code' => $address->postal_code,
            ]);

            // Shipping information
            $order->shipping_courier = $courier;
            $order->shipping_service = $service;
            $order->shipping_etd = $etd;
            // @phpstan-ignore assign.propertyType
            $order->weight_total_gram = $totalWeight;

            // Timestamps
            $order->placed_at = now();
            $order->source = 'web';

            $order->save();

            // Order Items
            foreach ($cart->items as $ci) {
                // Validasi stok sederhana (opsional)
                if ($ci->purchasable_type === ProductVariant::class && isset($ci->purchasable->stock_quantity)) {
                    if ($ci->purchasable->stock_quantity < $ci->quantity) {
                        throw new \RuntimeException('Stok varian tidak mencukupi: '.($ci->purchasable->name ?? ''));
                    }
                }
                if ($ci->purchasable_type === Product::class && isset($ci->purchasable->stock_quantity)) {
                    if ($ci->purchasable->stock_quantity < $ci->quantity) {
                        throw new \RuntimeException('Stok produk tidak mencukupi: '.($ci->purchasable->name ?? ''));
                    }
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'purchasable_type' => $ci->purchasable_type,
                    'purchasable_id' => $ci->purchasable_id,
                    'sku' => $ci->sku,
                    'name' => $ci->name,
                    'quantity' => $ci->quantity,
                    'price' => $ci->price,
                    'subtotal' => $ci->price * $ci->quantity,
                    'weight_gram' => $ci->weight_gram ?? 0,
                ]);
            }

            // Payment (create payment record)
            $payment = Payment::create([
                'order_id' => $order->id,
                'customer_id' => $customerId,
                'gross_amount' => $total,
                'currency' => 'IDR',
                'transaction_status' => 'pending',
                'provider' => $paymentMethod === 'midtrans' ? 'midtrans' : 'manual',
                'order_id_ref' => $orderNumber, // Store order number for Midtrans callback
                'transaction_time' => now(),
            ]);

            // Log payment creation
            PaymentLog::create([
                'payment_id' => $payment->id,
                'order_id' => $order->id,
                'type' => 'payment_created',
                'payload' => [
                    'method' => $paymentMethod,
                    'amount' => $total,
                    'currency' => 'IDR',
                    'order_number' => $orderNumber,
                    'message' => 'Payment created with method: '.$paymentMethod,
                ],
                'ip_address' => request()->ip(),
                'occurred_at' => now(),
            ]);

            // Generate Midtrans Snap Token untuk online payment
            $snapToken = null;
            if ($paymentMethod !== 'cod' && $paymentMethod !== 'manual_transfer') {
                try {
                    $midtransService = new MidtransService;
                    $snapToken = $midtransService->createSnapToken($order, $payment);
                } catch (\Exception $e) {
                    // Log error tapi jangan fail transaksi
                    \Log::error('Failed to create Midtrans snap token: '.$e->getMessage());
                }
            }

            // Kosongkan cart
            CartItem::where('cart_id', $cart->id)->delete();

            return [
                'success' => true,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'payment_id' => $payment->id,
                'snap_token' => $snapToken,
                'payment_method' => $paymentMethod,
                'redirect_url' => route('checkout.thankyou', $order->order_number),
                'message' => 'Pesanan berhasil dibuat. Silakan lanjutkan pembayaran.',
            ];
        });
    }

    /* ================= Helpers ================= */

    protected function mapCartItem(CartItem $ci): array
    {
        $type = $ci->purchasable_type;
        $name = $ci->name ?: '';
        $img = null;
        $url = '#';

        if ($type === Product::class) {
            /** @var Product $p */
            $p = $ci->purchasable;
            if ($p) {
                $name = $p->name;
                $url = url('/product/'.$p->slug);
                $img = method_exists($p, 'firstImageUrl') ? $p->firstImageUrl() : ($p->image_path ?? null);
            }
        } elseif ($type === ProductVariant::class) {
            /** @var ProductVariant $v */
            $v = $ci->purchasable;
            if ($v) {
                $name = ($v->product->name ?? '').' - '.($v->name ?? '');
                $url = url('/product/'.$v->product->slug);
                $img = method_exists($v->product, 'firstImageUrl') ? $v->product->firstImageUrl() : ($v->product->image_path ?? null);
            }
        }

        return [
            'id' => $ci->id,
            'sku' => $ci->sku,
            'name' => $name,
            'image' => $img ?? asset('images/placeholder.jpg'),
            'url' => $url,
            'quantity' => (int) $ci->quantity,
            'price' => (float) $ci->price,
            'subtotal' => (float) $ci->subtotal,
            'formatted_price' => 'Rp '.number_format((float) $ci->price, 0, ',', '.'),
            'formatted_subtotal' => 'Rp '.number_format((float) $ci->subtotal, 0, ',', '.'),
            'purchasable_type' => $type,
            'purchasable_id' => (int) $ci->purchasable_id,
        ];
    }

    protected function generateOrderNumber(): string
    {
        return 'INV'.now()->format('YmdHis').Str::upper(Str::random(4));
    }

    protected function normalizeEtd(?string $etd): string
    {
        if (! $etd) {
            return '1-3';
        }

        // Hapus kata "HARI", "hari", spasi berlebih
        $clean = trim(preg_replace('/\b(hari|HARI|days?|DAYS?)\b/i', '', $etd));

        // Jika kosong setelah cleaning, return default
        if (empty($clean)) {
            return '1-3';
        }

        return $clean;
    }
}
