<?php

namespace App\Livewire;

use App\Models\BannerSlider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class HeroArea extends Component
{
    /** @var array<int, array<string, mixed>> */
    public array $slides = [];

    /** Batas jumlah slide ditampilkan */
    public int $limit = 5;

    public function mount(int $limit = 5): void
    {
        try {
            $this->limit = max(1, min(20, (int) $limit));
            $this->slides = $this->fetchSlides($this->limit);
        } catch (\Throwable $e) {
            Log::error('HeroArea mount error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->slides = [];
        }
    }

    /**
     * Ambil data hero slides dari BannerSlider:
     * - Filter: is_active = true
     * - Urutkan: sort_order ASC (banner dengan sort_order terkecil muncul duluan)
     * - Limit sesuai parameter
     */
    protected function fetchSlides(int $limit): array
    {
        try {
            $banners = BannerSlider::query()
                ->where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->orderBy('id', 'desc')
                ->limit($limit)
                ->get();

            return $banners->map(function ($banner) {
                return [
                    'id' => $banner->id,
                    'name' => $banner->name ?? '',
                    'desc' => $banner->description ?? '',
                    'button_text' => $banner->button_text ?? 'Belanja Sekarang',
                    'link_url' => $banner->link_url ?? url('/shop'),
                    'image' => $this->toUrl($banner->image_path),
                    'image_108_53' => $this->toUrl($banner->image_path_108_53 ?? $banner->image_path),
                    'discount_percent' => $banner->discount_percent
                        ? max(0, min(100, (int) $banner->discount_percent))
                        : null,
                    'product_url' => $banner->link_url ?? url('/shop'),
                ];
            })->toArray();
        } catch (\Throwable $e) {
            Log::error('HeroArea fetchSlides error', [
                'message' => $e->getMessage(),
                'limit' => $limit,
            ]);

            return [];
        }
    }

    /** Ubah path penyimpanan ke URL publik (disk/public atau absolute), fallback placeholder */
    protected function toUrl(?string $path): string
    {
        if (! $path || trim((string) $path) === '') {
            return asset('images/placeholder.jpg');
        }
        if (preg_match('~^https?://~i', $path)) {
            return $path;
        }
        try {
            return Storage::url($path); // jika file disimpan di storage
        } catch (\Throwable $e) {
            return asset(ltrim($path, '/')); // fallback jika file ada di /public
        }
    }

    public function render()
    {
        return view('livewire.hero-area');
    }
}
