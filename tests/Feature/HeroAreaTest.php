<?php

use App\Livewire\HeroArea;
use App\Models\BannerSlider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('renders hero area component successfully', function () {
    Livewire::test(HeroArea::class)
        ->assertStatus(200)
        ->assertViewHas('slides');
});

it('displays active banner sliders in correct order', function () {
    // Create test banners
    $banner1 = BannerSlider::create([
        'name' => 'Summer Sale',
        'description' => 'Get up to 50% off on summer collection',
        'button_text' => 'Shop Now',
        'link_url' => '/shop/summer',
        'image_path' => 'banners/summer.jpg',
        'sort_order' => 2,
        'is_active' => true,
        'discount_percent' => 50,
    ]);

    $banner2 = BannerSlider::create([
        'name' => 'New Arrivals',
        'description' => 'Check out our latest products',
        'button_text' => 'Discover',
        'link_url' => '/shop/new',
        'image_path' => 'banners/new.jpg',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    $banner3 = BannerSlider::create([
        'name' => 'Inactive Banner',
        'description' => 'Should not appear',
        'button_text' => 'Click',
        'link_url' => '/shop',
        'image_path' => 'banners/inactive.jpg',
        'sort_order' => 0,
        'is_active' => false,
    ]);

    Livewire::test(HeroArea::class)
        ->assertStatus(200)
        ->assertViewHas('slides', function ($slides) use ($banner1, $banner2) {
            return count($slides) === 2
                && $slides[0]['id'] === $banner2->id // sort_order 1 comes first
                && $slides[1]['id'] === $banner1->id // sort_order 2 comes second
                && $slides[0]['name'] === 'New Arrivals'
                && $slides[1]['discount_percent'] === 50;
        });
});

it('respects limit parameter', function () {
    // Create 10 banners
    for ($i = 1; $i <= 10; $i++) {
        BannerSlider::create([
            'name' => "Banner {$i}",
            'description' => "Description {$i}",
            'button_text' => 'Shop',
            'link_url' => '/shop',
            'image_path' => "banners/banner{$i}.jpg",
            'sort_order' => $i,
            'is_active' => true,
        ]);
    }

    Livewire::test(HeroArea::class, ['limit' => 3])
        ->assertStatus(200)
        ->assertViewHas('slides', function ($slides) {
            return count($slides) === 3;
        });
});

it('returns empty array when no active banners exist', function () {
    // Create only inactive banners
    BannerSlider::create([
        'name' => 'Inactive',
        'description' => 'Test',
        'button_text' => 'Click',
        'link_url' => '/shop',
        'image_path' => 'test.jpg',
        'is_active' => false,
    ]);

    Livewire::test(HeroArea::class)
        ->assertStatus(200)
        ->assertViewHas('slides', function ($slides) {
            return count($slides) === 0;
        });
});

it('validates and clamps discount percent to 0-100 range', function () {
    $banner = BannerSlider::create([
        'name' => 'Test Banner',
        'description' => 'Test',
        'button_text' => 'Shop',
        'link_url' => '/shop',
        'image_path' => 'test.jpg',
        'is_active' => true,
        'discount_percent' => 150, // Invalid: over 100
    ]);

    Livewire::test(HeroArea::class)
        ->assertStatus(200)
        ->assertViewHas('slides', function ($slides) {
            return $slides[0]['discount_percent'] === 100; // Clamped to max 100
        });
});
