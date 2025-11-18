<?php

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Models\Brand;
use App\Models\Inventory\Product as InventoryProduct;
use App\Models\Inventory\ProductVariant as InventoryProductVariant;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

// Helper function to generate unique SKU
function uniqueSku(string $prefix = 'TEST'): string
{
    return $prefix.'-'.uniqid();
}

it('disables all fields except catalog_product_id by default', function () {
    $admin = User::factory()->create();

    Livewire::actingAs($admin)
        ->test(CreateProduct::class)
        ->assertFormFieldIsEnabled('catalog_product_id')
        ->assertFormFieldIsDisabled('name')
        ->assertFormFieldIsDisabled('sku')
        ->assertFormFieldIsDisabled('price')
        ->assertFormFieldIsDisabled('stock')
        ->assertFormFieldIsDisabled('status');
});

it('enables fields after selecting catalog product', function () {
    $admin = User::factory()->create();

    // Create an inventory product with unique SKU
    $inventoryProduct = InventoryProduct::create([
        'sku' => uniqueSku(),
        'name' => 'Test Product',
        'description' => 'Test Description',
        'is_active' => true,
        'brand' => 'Test Brand',
    ]);

    Livewire::actingAs($admin)
        ->test(CreateProduct::class)
        ->fillForm([
            'catalog_product_id' => $inventoryProduct->id,
        ])
        ->assertFormFieldIsEnabled('catalog_product_id')
        ->assertFormFieldIsEnabled('name')
        ->assertFormFieldIsEnabled('sku')
        ->assertFormFieldIsEnabled('price')
        ->assertFormFieldIsEnabled('stock')
        ->assertFormFieldIsEnabled('status');
});

it('auto-fills fields from catalog product', function () {
    $admin = User::factory()->create();

    // Create an inventory product
    $sku = uniqueSku('AUTO');
    $inventoryProduct = InventoryProduct::create([
        'sku' => $sku,
        'name' => 'Auto Fill Product',
        'description' => 'Auto Description',
        'is_active' => true,
        'brand' => 'Auto Brand',
        'model' => 'Model-X',
    ]);

    $component = Livewire::actingAs($admin)
        ->test(CreateProduct::class)
        ->fillForm([
            'catalog_product_id' => $inventoryProduct->sku,
        ])
        ->assertFormSet([
            'name' => 'Auto Fill Product',
            'sku' => $sku,
            'short_description' => 'Brand: Auto Brand | Model: Model-X',
        ]);

    // Check description is set (might be wrapped in HTML by RichEditor)
    $formData = $component->get('data');
    expect($formData['description'])->not->toBeNull();
});

it('auto-creates brand from catalog product', function () {
    $admin = User::factory()->create();

    // Ensure brand doesn't exist yet
    expect(Brand::query()->where('slug', 'test-brand')->exists())->toBeFalse();

    // Create an inventory product with brand
    $inventoryProduct = InventoryProduct::create([
        'sku' => uniqueSku('BRAND'),
        'name' => 'Brand Test Product',
        'description' => 'Brand Test Description',
        'is_active' => true,
        'brand' => 'Test Brand',
    ]);

    $component = Livewire::actingAs($admin)
        ->test(CreateProduct::class)
        ->fillForm([
            'catalog_product_id' => $inventoryProduct->sku,
        ]);

    // Brand should be auto-created
    expect(Brand::query()->where('slug', 'test-brand')->exists())->toBeTrue();

    $brand = Brand::query()->where('slug', 'test-brand')->first();
    expect($brand->name)->toBe('Test Brand');
    expect($brand->is_active)->toBe(1); // Database stores as integer
});

it('auto-populates variants from catalog product', function () {
    $admin = User::factory()->create();

    // Create an inventory product
    $sku = uniqueSku('VARIANT');
    $inventoryProduct = InventoryProduct::create([
        'sku' => $sku,
        'name' => 'Product With Auto Variants',
        'description' => 'Variant Auto Test',
        'is_active' => true,
    ]);

    // Create variants
    $variant1Sku = $sku.'-PINK-XS';
    InventoryProductVariant::create([
        'product_id' => $inventoryProduct->id,
        'sku_variant' => $variant1Sku,
        'color' => 'pink',
        'size' => 'XS',
        'price' => 78500.49,
        'cost_price' => 50000,
        'status' => 'active',
    ]);

    $variant2Sku = $sku.'-STRAWBERRY-S';
    InventoryProductVariant::create([
        'product_id' => $inventoryProduct->id,
        'sku_variant' => $variant2Sku,
        'color' => 'Strawberry',
        'size' => 'S',
        'price' => 83078.24,
        'cost_price' => 55000,
        'status' => 'active',
    ]);

    $component = Livewire::actingAs($admin)
        ->test(CreateProduct::class)
        ->fillForm([
            'catalog_product_id' => $inventoryProduct->sku,
        ]);

    // Check if variants are auto-populated
    $formData = $component->get('data');

    expect($formData['variants'])->toBeArray();
    expect($formData['variants'])->toHaveCount(2);

    // Check first variant
    expect($formData['variants'][0]['sku'])->toBe($variant1Sku);
    expect($formData['variants'][0]['color'])->toBe('pink');
    expect($formData['variants'][0]['size'])->toBe('XS');
    expect($formData['variants'][0]['price'])->toBe('78500.49');
    expect($formData['variants'][0]['is_active'])->toBeTrue();

    // Check second variant
    expect($formData['variants'][1]['sku'])->toBe($variant2Sku);
    expect($formData['variants'][1]['color'])->toBe('strawberry');
    expect($formData['variants'][1]['size'])->toBe('S');
    expect($formData['variants'][1]['price'])->toBe('83078.24');
});

it('can create product with catalog selection', function () {
    $admin = User::factory()->create();

    $sku = uniqueSku('CREATE');

    // Create inventory product
    $inventoryProduct = InventoryProduct::create([
        'sku' => $sku,
        'name' => 'Create Test Product',
        'brand' => 'Create Brand',
        'model' => 'Test Model',
        'description' => 'Create Test Description',
        'is_active' => true,
    ]);

    $component = Livewire::actingAs($admin)
        ->test(CreateProduct::class)
        ->fillForm([
            'catalog_product_id' => $inventoryProduct->sku,
            'price' => 100000,
            'stock' => 10,
            'weight_gram' => 500,
            'currency' => 'IDR',
            'status' => 'active',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $product = Product::query()->where('sku', $sku)->first();

    expect($product)->not->toBeNull();
    expect($product->name)->toBe('Create Test Product');
    expect($product->price)->toBe('100000.00');
    expect($product->stock)->toBe(10);
});
