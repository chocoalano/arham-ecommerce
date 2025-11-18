<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Voucher;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        // Brands
        $brands = Brand::factory()->count(6)->create();

        // Categories (parents + children)
        $parents = ProductCategory::factory()->count(5)->create();
        foreach ($parents as $parent) {
            ProductCategory::factory()->count(rand(2, 4))->create(['parent_id' => $parent->id]);
        }

        // Vouchers
        Voucher::factory()->count(5)->create();

        // Products
        $allCategories = ProductCategory::all();
        foreach (range(1, 40) as $_) {
            $product = Product::factory()->create([
                'brand_id' => $brands->random()->id,
            ]);

            // Attach 1-3 categories (child if exists else parent)
            $cats = $allCategories->whereNotNull('parent_id');
            if ($cats->isEmpty()) {
                $cats = $allCategories;
            }
            $product->categories()->sync($cats->random(rand(1, 3))->pluck('id')->toArray());

            // Images: 1 thumbnail + 1-3 gallery
            $thumb = ProductImage::factory()->create([
                'product_id' => $product->id,
                'is_thumbnail' => true,
                'sort_order' => 0,
            ]);
            $count = rand(1, 3);
            for ($i = 1; $i <= $count; $i++) {
                ProductImage::factory()->create([
                    'product_id' => $product->id,
                    'sort_order' => $i,
                ]);
            }

            // Variants: 0-3
            $variantCount = rand(0, 3);
            for ($i = 0; $i < $variantCount; $i++) {
                ProductVariant::factory()->create([
                    'product_id' => $product->id,
                ]);
            }
        }
    }
}
