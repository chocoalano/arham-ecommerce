<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentLog;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Shipment;
use App\Models\ShippingQuote;
use App\Models\Voucher;
use Illuminate\Database\Seeder;

class CommerceSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $vouchers = Voucher::inRandomOrder()->get();

        // Create carts with items and quotes
        foreach ($customers as $c) {
            $cart = Cart::factory()->create([
                'customer_id' => $c->id,
                'address_id' => Address::where('customer_id', $c->id)->inRandomOrder()->value('id'),
                'voucher_id' => $vouchers->isNotEmpty() && rand(0, 1) ? $vouchers->first()->id : null,
            ]);

            $itemsCount = rand(1, 4);
            for ($i = 0; $i < $itemsCount; $i++) {
                if (rand(0, 1) && ProductVariant::count() > 0) {
                    $v = ProductVariant::inRandomOrder()->first();
                    $price = $v->effectivePrice();
                    $qty = rand(1, 3);
                    CartItem::factory()->create([
                        'cart_id' => $cart->id,
                        'purchasable_type' => ProductVariant::class,
                        'purchasable_id' => $v->id,
                        'sku' => $v->sku,
                        'name' => $v->name,
                        'weight_gram' => $v->weight_gram,
                        'quantity' => $qty,
                        'price' => $price,
                        'subtotal' => $price * $qty,
                    ]);
                } else {
                    $p = Product::inRandomOrder()->first();
                    $price = $p->effective_price;
                    $qty = rand(1, 3);
                    CartItem::factory()->create([
                        'cart_id' => $cart->id,
                        'purchasable_type' => Product::class,
                        'purchasable_id' => $p->id,
                        'sku' => $p->sku,
                        'name' => $p->name,
                        'weight_gram' => $p->weight_gram,
                        'quantity' => $qty,
                        'price' => $price,
                        'subtotal' => $price * $qty,
                    ]);
                }
            }

            // Add a couple of shipping quotes
            foreach (range(1, rand(1, 3)) as $_) {
                ShippingQuote::factory()->create([
                    'cart_id' => $cart->id,
                    'address_id' => $cart->address_id,
                ]);
            }
        }

        // Convert some carts to orders
        $carts = Cart::inRandomOrder()->take(7)->get();
        foreach ($carts as $cart) {
            $items = $cart->items;
            if ($items->isEmpty()) {
                continue;
            }

            $subtotal = $items->sum('subtotal');
            $discount = 0;
            if ($cart->voucher) {
                if ($cart->voucher->type === 'percent') {
                    $discount = min($subtotal * ($cart->voucher->value / 100), (float) ($cart->voucher->max_discount ?? $subtotal));
                } elseif ($cart->voucher->type === 'fixed') {
                    $discount = min($cart->voucher->value, $subtotal);
                }
            }
            $shipping = ShippingQuote::where('cart_id', $cart->id)->inRandomOrder()->value('cost') ?? 0;
            $tax = 0;
            $grand = $subtotal - $discount + $tax + $shipping;

            $order = Order::factory()->create([
                'customer_id' => $cart->customer_id,
                'voucher_id' => $cart->voucher_id,
                'billing_address_id' => $cart->address_id,
                'billing_address_snapshot' => 'Snapshot billing address at ordering time',
                'shipping_address_id' => $cart->address_id,
                'shipping_address_snapshot' => 'Snapshot shipping address at ordering time',
                'subtotal' => $subtotal,
                'discount_total' => $discount,
                'tax_total' => $tax,
                'shipping_total' => $shipping,
                'grand_total' => $grand,
                'shipping_courier' => ShippingQuote::where('cart_id', $cart->id)->value('courier'),
                'shipping_service' => ShippingQuote::where('cart_id', $cart->id)->value('service'),
                'shipping_cost' => $shipping,
                'shipping_etd' => ShippingQuote::where('cart_id', $cart->id)->value('etd'),
                'status' => 'awaiting_payment',
            ]);

            $totalWeight = 0;
            foreach ($items as $ci) {
                OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'purchasable_type' => $ci->purchasable_type,
                    'purchasable_id' => $ci->purchasable_id,
                    'sku' => $ci->sku,
                    'name' => $ci->name,
                    'weight_gram' => $ci->weight_gram,
                    'quantity' => $ci->quantity,
                    'price' => $ci->price,
                    'subtotal' => $ci->subtotal,
                    'meta' => $ci->meta,
                ]);
                $totalWeight += $ci->weight_gram * $ci->quantity;

                // reduce product stock (only for Product type)
                if ($ci->purchasable_type === Product::class) {
                    $product = Product::find($ci->purchasable_id);
                    if ($product) {
                        $product->adjustStock(-$ci->quantity);
                    }
                }
            }
            $order->update(['weight_total_gram' => $totalWeight]);

            // Payment
            $payment = Payment::factory()->create([
                'order_id' => $order->id,
                'customer_id' => $order->customer_id,
                'gross_amount' => $grand,
                'transaction_status' => 'settlement',
                'settlement_time' => now(),
            ]);
            PaymentLog::factory()->create([
                'payment_id' => $payment->id,
                'order_id' => $order->id,
            ]);

            // Shipment
            $shipment = Shipment::factory()->create([
                'order_id' => $order->id,
                'courier' => $order->shipping_courier ?? 'jne',
                'service' => $order->shipping_service ?? 'REG',
                'cost' => $order->shipping_total,
                'status' => 'shipped',
                'shipped_at' => now(),
            ]);

            $order->update([
                'status' => 'processing',
                'paid_at' => now(),
            ]);
        }
    }
}
