<?php

use App\Livewire\FeaturedCategories;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('renders featured categories component successfully', function () {
    Livewire::test(FeaturedCategories::class)
        ->assertStatus(200)
        ->assertViewHas('categories');
});

it('only displays categories where highlight is true', function () {
    // Create categories with highlight = true
    $highlightedCategory1 = ProductCategory::create([
        'name' => 'Featured Electronics',
        'slug' => 'featured-electronics',
        'is_active' => true,
        'highlight' => true,
        'sort_order' => 1,
    ]);

    $highlightedCategory2 = ProductCategory::create([
        'name' => 'Featured Fashion',
        'slug' => 'featured-fashion',
        'is_active' => true,
        'highlight' => true,
        'sort_order' => 2,
    ]);

    // Create categories with highlight = false (should NOT appear)
    $normalCategory = ProductCategory::create([
        'name' => 'Normal Category',
        'slug' => 'normal-category',
        'is_active' => true,
        'highlight' => false,
        'sort_order' => 3,
    ]);

    // Create products for highlighted categories
    $product1 = Product::factory()->create(['status' => 'active']);
    $product2 = Product::factory()->create(['status' => 'active']);
    $product3 = Product::factory()->create(['status' => 'active']);

    $highlightedCategory1->products()->attach($product1->id);
    $highlightedCategory2->products()->attach($product2->id);
    $normalCategory->products()->attach($product3->id);

    Livewire::test(FeaturedCategories::class)
        ->assertStatus(200)
        ->assertViewHas('categories', function ($categories) use ($highlightedCategory1, $highlightedCategory2, $normalCategory) {
            // Should only have 2 categories (highlight = true)
            expect($categories)->toHaveCount(2);

            // Extract IDs from categories
            $categoryIds = collect($categories)->pluck('id')->toArray();

            // Should contain highlighted categories
            expect($categoryIds)->toContain($highlightedCategory1->id);
            expect($categoryIds)->toContain($highlightedCategory2->id);

            // Should NOT contain normal category (highlight = false)
            expect($categoryIds)->not->toContain($normalCategory->id);

            return true;
        });
});

it('filters inactive categories even if highlighted', function () {
    // Create highlighted but inactive category (should NOT appear)
    $inactiveCategory = ProductCategory::create([
        'name' => 'Inactive Highlighted',
        'slug' => 'inactive-highlighted',
        'is_active' => false,
        'highlight' => true,
    ]);

    // Create active and highlighted category (should appear)
    $activeCategory = ProductCategory::create([
        'name' => 'Active Highlighted',
        'slug' => 'active-highlighted',
        'is_active' => true,
        'highlight' => true,
    ]);

    // Add product to active category
    $product = Product::factory()->create(['status' => 'active']);
    $activeCategory->products()->attach($product->id);

    Livewire::test(FeaturedCategories::class)
        ->assertStatus(200)
        ->assertViewHas('categories', function ($categories) use ($activeCategory, $inactiveCategory) {
            $categoryIds = collect($categories)->pluck('id')->toArray();

            // Should only contain active category
            expect($categories)->toHaveCount(1);
            expect($categoryIds)->toContain($activeCategory->id);
            expect($categoryIds)->not->toContain($inactiveCategory->id);

            return true;
        });
});

it('respects hideEmpty parameter to filter categories without products', function () {
    // Category with products
    $categoryWithProducts = ProductCategory::create([
        'name' => 'Category With Products',
        'slug' => 'category-with-products',
        'is_active' => true,
        'highlight' => true,
    ]);

    // Category without products
    $categoryWithoutProducts = ProductCategory::create([
        'name' => 'Category Without Products',
        'slug' => 'category-without-products',
        'is_active' => true,
        'highlight' => true,
    ]);

    $product = Product::factory()->create(['status' => 'active']);
    $categoryWithProducts->products()->attach($product->id);

    // Test with hideEmpty = true (default)
    Livewire::test(FeaturedCategories::class, ['hideEmpty' => true])
        ->assertStatus(200)
        ->assertViewHas('categories', function ($categories) use ($categoryWithProducts) {
            // Should only show category with products
            expect($categories)->toHaveCount(1);
            expect($categories[0]['id'])->toBe($categoryWithProducts->id);

            return true;
        });

    // Test with hideEmpty = false
    Livewire::test(FeaturedCategories::class, ['hideEmpty' => false])
        ->assertStatus(200)
        ->assertViewHas('categories', function ($categories) {
            // Should show both categories
            expect($categories)->toHaveCount(2);

            return true;
        });
});

it('respects limit parameter', function () {
    // Create 10 highlighted categories with products
    for ($i = 1; $i <= 10; $i++) {
        $category = ProductCategory::create([
            'name' => "Category {$i}",
            'slug' => "category-{$i}",
            'is_active' => true,
            'highlight' => true,
        ]);

        $product = Product::factory()->create(['status' => 'active']);
        $category->products()->attach($product->id);
    }

    Livewire::test(FeaturedCategories::class, ['limit' => 3])
        ->assertStatus(200)
        ->assertViewHas('categories', function ($categories) {
            return count($categories) === 3;
        });
});
