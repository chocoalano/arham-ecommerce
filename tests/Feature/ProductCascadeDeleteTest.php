<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductReview;
use App\Models\ProductVariant;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create necessary test data
    $this->category = ProductCategory::factory()->create();
    $this->customer = Customer::factory()->create();
});

it('soft deletes product and cascades to variants', function () {
    $product = Product::factory()->create();
    $variant1 = ProductVariant::factory()->for($product)->create(['sku' => 'VAR-001']);
    $variant2 = ProductVariant::factory()->for($product)->create(['sku' => 'VAR-002']);

    // Soft delete the product
    $product->delete();

    // Check product is soft deleted
    expect($product->fresh()->trashed())->toBeTrue();

    // Check variants are also soft deleted (cascaded)
    expect($variant1->fresh()->trashed())->toBeTrue();
    expect($variant2->fresh()->trashed())->toBeTrue();
});

it('restores product and cascades to variants', function () {
    $product = Product::factory()->create();
    $variant1 = ProductVariant::factory()->for($product)->create(['sku' => 'VAR-001']);
    $variant2 = ProductVariant::factory()->for($product)->create(['sku' => 'VAR-002']);

    // Soft delete then restore
    $product->delete();
    $product->restore();

    // Check product is restored
    expect($product->fresh()->trashed())->toBeFalse();

    // Check variants are also restored (cascaded)
    expect($variant1->fresh()->trashed())->toBeFalse();
    expect($variant2->fresh()->trashed())->toBeFalse();
});

it('force deletes product and all related data', function () {
    $product = Product::factory()->create();

    // Create relations
    $variant = ProductVariant::factory()->for($product)->create(['sku' => 'VAR-001']);
    $image = ProductImage::factory()->for($product)->create();
    $product->categories()->attach($this->category->id);

    // Create morphMany relations for product
    $productReview = ProductReview::factory()->create([
        'reviewable_type' => Product::class,
        'reviewable_id' => $product->id,
        'customer_id' => $this->customer->id,
    ]);

    $wishlist = Wishlist::factory()->for($this->customer)->create();
    $productWishlistItem = WishlistItem::factory()->for($wishlist)->create([
        'purchasable_type' => Product::class,
        'purchasable_id' => $product->id,
    ]);

    $cart = Cart::factory()->for($this->customer)->create();
    $productCartItem = CartItem::factory()->for($cart)->create([
        'purchasable_type' => Product::class,
        'purchasable_id' => $product->id,
        'quantity' => 2,
    ]);

    // Create morphMany relations for variant
    $variantReview = ProductReview::factory()->create([
        'reviewable_type' => ProductVariant::class,
        'reviewable_id' => $variant->id,
        'customer_id' => $this->customer->id,
    ]);

    $variantWishlistItem = WishlistItem::factory()->for($wishlist)->create([
        'purchasable_type' => ProductVariant::class,
        'purchasable_id' => $variant->id,
    ]);

    $variantCartItem = CartItem::factory()->for($cart)->create([
        'purchasable_type' => ProductVariant::class,
        'purchasable_id' => $variant->id,
        'quantity' => 1,
    ]);

    // Get IDs before deletion
    $productId = $product->id;
    $variantId = $variant->id;
    $imageId = $image->id;

    // Force delete the product
    $product->forceDelete();

    // Check product is permanently deleted
    expect(Product::withTrashed()->find($productId))->toBeNull();

    // Check variant is permanently deleted
    expect(ProductVariant::withTrashed()->find($variantId))->toBeNull();

    // Check image is permanently deleted
    expect(ProductImage::find($imageId))->toBeNull();

    // Check categories are detached
    expect($this->category->products()->count())->toBe(0);

    // Check product morphMany relations are deleted
    expect(ProductReview::find($productReview->id))->toBeNull();
    expect(WishlistItem::find($productWishlistItem->id))->toBeNull();
    expect(CartItem::find($productCartItem->id))->toBeNull();

    // Check variant morphMany relations are deleted
    expect(ProductReview::find($variantReview->id))->toBeNull();
    expect(WishlistItem::find($variantWishlistItem->id))->toBeNull();
    expect(CartItem::find($variantCartItem->id))->toBeNull();
});

it('force deletes variant and all its morphMany relations', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->for($product)->create(['sku' => 'VAR-001']);

    // Create morphMany relations for variant
    $review = ProductReview::factory()->create([
        'reviewable_type' => ProductVariant::class,
        'reviewable_id' => $variant->id,
        'customer_id' => $this->customer->id,
    ]);

    $wishlist = Wishlist::factory()->for($this->customer)->create();
    $wishlistItem = WishlistItem::factory()->for($wishlist)->create([
        'purchasable_type' => ProductVariant::class,
        'purchasable_id' => $variant->id,
    ]);

    $cart = Cart::factory()->for($this->customer)->create();
    $cartItem = CartItem::factory()->for($cart)->create([
        'purchasable_type' => ProductVariant::class,
        'purchasable_id' => $variant->id,
        'quantity' => 1,
    ]);

    // Get IDs before deletion
    $variantId = $variant->id;
    $reviewId = $review->id;
    $wishlistItemId = $wishlistItem->id;
    $cartItemId = $cartItem->id;

    // Force delete the variant
    $variant->forceDelete();

    // Check variant is permanently deleted
    expect(ProductVariant::withTrashed()->find($variantId))->toBeNull();

    // Check morphMany relations are deleted
    expect(ProductReview::find($reviewId))->toBeNull();
    expect(WishlistItem::find($wishlistItemId))->toBeNull();
    expect(CartItem::find($cartItemId))->toBeNull();

    // Check product still exists
    expect($product->fresh())->not->toBeNull();
});

it('prevents duplicate variant SKU in same product during creation', function () {
    $product = Product::factory()->create();
    $variant1 = ProductVariant::factory()->for($product)->create(['sku' => 'VAR-DUPLICATE']);

    // Try to create another variant with same SKU in same product
    // Should use updateOrCreate, so it updates instead of creates
    $variant2 = $product->variants()->updateOrCreate(
        ['sku' => 'VAR-DUPLICATE'],
        ['name' => 'Updated Variant', 'price' => 200.00]
    );

    // Should only have 1 variant
    expect($product->variants()->count())->toBe(1);
    expect($variant1->id)->toBe($variant2->id);
    expect($variant2->fresh()->name)->toBe('Updated Variant');
    expect((float) $variant2->fresh()->price)->toBe(200.00);
});

it('allows same variant SKU across different products', function () {
    $product1 = Product::factory()->create(['sku' => 'PROD-001']);
    $product2 = Product::factory()->create(['sku' => 'PROD-002']);

    // Create variants with same SKU but different products
    $variant1 = ProductVariant::factory()->for($product1)->create(['sku' => 'VAR-SHARED']);
    $variant2 = ProductVariant::factory()->for($product2)->create(['sku' => 'VAR-SHARED']);

    // Should allow both variants
    expect($variant1->id)->not->toBe($variant2->id);
    expect($variant1->sku)->toBe($variant2->sku);
    expect($variant1->product_id)->toBe($product1->id);
    expect($variant2->product_id)->toBe($product2->id);
});

it('handles complex cascade scenario with multiple relations', function () {
    // Create product with full relations
    $product = Product::factory()->create();
    $product->categories()->attach($this->category->id);

    $variant1 = ProductVariant::factory()->for($product)->create(['sku' => 'VAR-001']);
    $variant2 = ProductVariant::factory()->for($product)->create(['sku' => 'VAR-002']);

    $image1 = ProductImage::factory()->for($product)->create();
    $image2 = ProductImage::factory()->for($product)->create();

    // Create multiple customers and their interactions
    $customer2 = Customer::factory()->create();

    $cart1 = Cart::factory()->for($this->customer)->create();
    $cart2 = Cart::factory()->for($customer2)->create();

    // Customer 1: cart items for product and variant1
    CartItem::factory()->for($cart1)->create([
        'purchasable_type' => Product::class,
        'purchasable_id' => $product->id,
        'quantity' => 2,
    ]);

    CartItem::factory()->for($cart1)->create([
        'purchasable_type' => ProductVariant::class,
        'purchasable_id' => $variant1->id,
        'quantity' => 1,
    ]);

    // Customer 2: cart items for variant2
    CartItem::factory()->for($cart2)->create([
        'purchasable_type' => ProductVariant::class,
        'purchasable_id' => $variant2->id,
        'quantity' => 3,
    ]);

    // Reviews from both customers
    ProductReview::factory()->create([
        'reviewable_type' => Product::class,
        'reviewable_id' => $product->id,
        'customer_id' => $this->customer->id,
    ]);

    ProductReview::factory()->create([
        'reviewable_type' => ProductVariant::class,
        'reviewable_id' => $variant1->id,
        'customer_id' => $customer2->id,
    ]);

    // Count before deletion
    $totalCartItems = CartItem::count();
    $totalReviews = ProductReview::count();
    $totalImages = ProductImage::count();
    $totalVariants = ProductVariant::count();

    expect($totalCartItems)->toBe(3);
    expect($totalReviews)->toBe(2);
    expect($totalImages)->toBe(2);
    expect($totalVariants)->toBe(2);

    // Force delete the product
    $product->forceDelete();

    // All related data should be deleted
    expect(CartItem::count())->toBe(0);
    expect(ProductReview::count())->toBe(0);
    expect(ProductImage::count())->toBe(0);
    expect(ProductVariant::withTrashed()->count())->toBe(0);
    expect(Product::withTrashed()->count())->toBe(0);

    // Categories should still exist but detached
    expect(ProductCategory::count())->toBe(1);
    expect($this->category->fresh()->products()->count())->toBe(0);

    // Carts should still exist (not deleted)
    expect(Cart::count())->toBe(2);
});
