<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::factory()->count(10)->create();

        foreach ($customers as $c) {
            // Addresses
            $addr1 = Address::factory()->create(['customer_id' => $c->id, 'is_default_shipping' => true]);
            $addr2 = Address::factory()->create(['customer_id' => $c->id, 'is_default_billing' => true]);

            // Wishlist (unique per customer)
            $wishlist = Wishlist::factory()->create(['customer_id' => $c->id]);

            // Wishlist items (mix product/variant)
            $loop = rand(1, 4);
            for ($i = 0; $i < $loop; $i++) {
                if (rand(0, 1) && ProductVariant::count() > 0) {
                    $v = ProductVariant::inRandomOrder()->first();
                    WishlistItem::factory()->create([
                        'wishlist_id' => $wishlist->id,
                        'purchasable_type' => ProductVariant::class,
                        'purchasable_id' => $v->id,
                        'price_at_addition' => $v->effectivePrice(),
                    ]);
                } else {
                    $p = Product::inRandomOrder()->first();
                    WishlistItem::factory()->create([
                        'wishlist_id' => $wishlist->id,
                        'purchasable_type' => Product::class,
                        'purchasable_id' => $p->id,
                        'price_at_addition' => $p->effective_price,
                    ]);
                }
            }
        }
    }
}
