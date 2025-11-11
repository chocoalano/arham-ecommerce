<?php

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

it('deletes old file when updating image path', function () {
    $product = Product::factory()->create();

    // Create image with initial file
    $oldFile = UploadedFile::fake()->image('old-product.jpg');
    $oldPath = $oldFile->store('products', 'public');

    $image = ProductImage::create([
        'product_id' => $product->id,
        'path' => $oldPath,
        'alt_text' => 'Old Image',
    ]);

    expect(Storage::disk('public')->exists($oldPath))->toBeTrue();

    // Update with new file
    $newFile = UploadedFile::fake()->image('new-product.jpg');
    $newPath = $newFile->store('products', 'public');

    $image->update(['path' => $newPath]);

    // Old file should be deleted, new file should exist
    expect(Storage::disk('public')->exists($oldPath))->toBeFalse();
    expect(Storage::disk('public')->exists($newPath))->toBeTrue();
});

it('deletes file when image record is deleted', function () {
    $product = Product::factory()->create();

    $file = UploadedFile::fake()->image('product.jpg');
    $path = $file->store('products', 'public');

    $image = ProductImage::create([
        'product_id' => $product->id,
        'path' => $path,
        'alt_text' => 'Product Image',
    ]);

    expect(Storage::disk('public')->exists($path))->toBeTrue();

    $image->delete();

    expect(Storage::disk('public')->exists($path))->toBeFalse();
});

it('can create multiple images for a product', function () {
    $product = Product::factory()->create();

    $image1 = ProductImage::create([
        'product_id' => $product->id,
        'path' => 'products/image1.jpg',
        'alt_text' => 'Image 1',
        'is_thumbnail' => false,
        'sort_order' => 1,
    ]);

    $image2 = ProductImage::create([
        'product_id' => $product->id,
        'path' => 'products/image2.jpg',
        'alt_text' => 'Image 2',
        'is_thumbnail' => true,
        'sort_order' => 0,
    ]);

    expect($product->images)->toHaveCount(2);
    expect($image2->is_thumbnail)->toBeTrue();
    expect($image1->is_thumbnail)->toBeFalse();
});
