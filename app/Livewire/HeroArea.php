<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductReview;
use App\Models\ProductVariant;
// Models
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
        $this->limit = $limit;
        $this->slides = $this->fetchSlides($this->limit);
    }

    /**
     * Ambil data hero slides:
     * - Produk: status = 'active', is_featured = 1
     * - Gambar: prioritas thumbnail (product_images.is_thumbnail=1), fallback gambar pertama (sort_order terkecil)
     * - Harga: final product price (sale_price||price) + FROM variant termurah (min COALESCE(sale_price, price))
     * - Rating: rata2 & jumlah ulasan approved (ProductReview polymorphic)
     * - Urutkan terbaru (COALESCE(published_at, created_at) DESC)
     */
    protected function fetchSlides(int $limit): array
    {
        $cacheKey = "hero_slides_v3_{$limit}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($limit) {
            // Subquery: gambar pertama (fallback) per product_id
            $firstImageSub = ProductImage::query()
                ->select('product_id', DB::raw('MIN(sort_order) as min_sort'))
                ->groupBy('product_id');

            // Subquery: min harga varian aktif
            $minVariantPriceSub = ProductVariant::query()
                ->selectRaw('MIN(COALESCE(sale_price, price))')
                ->whereColumn('product_id', 'products.id')
                ->where('is_active', true);

            // Subquery: avg rating (approved) untuk product
            $avgRatingSub = ProductReview::query()
                ->selectRaw('AVG(rating)')
                ->where('status', 'approved')
                ->where('reviewable_type', Product::class)
                ->whereColumn('reviewable_id', 'products.id');

            // Subquery: count review (approved)
            $countReviewSub = ProductReview::query()
                ->selectRaw('COUNT(*)')
                ->where('status', 'approved')
                ->where('reviewable_type', Product::class)
                ->whereColumn('reviewable_id', 'products.id');

            $rows = Product::query()
                // Join thumbnail
                ->leftJoin('product_images as thumb', function ($join) {
                    $join->on('thumb.product_id', '=', 'products.id')
                        ->where('thumb.is_thumbnail', '=', 1);
                })
                // Join subquery min sort_order, lalu join image fallback berdasar min_sort
                ->leftJoinSub($firstImageSub, 'fi', function ($join) {
                    $join->on('fi.product_id', '=', 'products.id');
                })
                ->leftJoin('product_images as img', function ($join) {
                    $join->on('img.product_id', '=', 'products.id')
                        ->on('img.sort_order', '=', 'fi.min_sort');
                })
                ->where('products.status', 'active')
                ->where('products.is_featured', true)
                ->select([
                    'products.id',
                    'products.slug',
                    'products.name',
                    'products.short_description',
                    'products.price',
                    'products.sale_price',
                    DB::raw('COALESCE(thumb.path, img.path) as image_path'),
                    DB::raw('COALESCE(thumb.path_ratio_27_28, img.path_ratio_27_28) as image_27_28'),
                    DB::raw('COALESCE(thumb.path_ratio_108_53, img.path_ratio_108_53) as image_108_53'),
                    DB::raw('COALESCE(thumb.path_ratio_51_52, img.path_ratio_51_52) as image_51_52'),
                    DB::raw('COALESCE(thumb.path_ratio_99_119, img.path_ratio_99_119) as image_99_119'),
                    DB::raw('COALESCE(products.created_at) as published_at'),
                ])
                ->selectSub($minVariantPriceSub, 'min_variant_price')
                ->selectSub($avgRatingSub, 'avg_rating')
                ->selectSub($countReviewSub, 'reviews_count')
                ->orderByDesc(DB::raw('COALESCE(products.created_at)'))
                ->limit($limit)
                ->get();

            return $rows->map(function ($row) {
                // Harga produk (bukan varian)
                $productPrice = (float) ($row->price ?? 0);
                $productSale = $row->sale_price !== null ? (float) $row->sale_price : null;
                $productFinal = ($productSale !== null && $productSale > 0 && $productSale < $productPrice)
                    ? $productSale
                    : $productPrice;

                // Harga varian termurah (jika ada & aktif)
                $variantFrom = $row->min_variant_price !== null ? (float) $row->min_variant_price : null;

                // Diskon (%) dihitung dari level product saja (bukan varian)
                $discountPct = ($productSale !== null && $productSale > 0 && $productSale < $productPrice)
                    ? (int) round((($productPrice - $productSale) / max(1, $productPrice)) * 100)
                    : null;

                // Rating & jumlah ulasan
                $avgRating = $row->avg_rating !== null ? round((float) $row->avg_rating, 1) : null;
                $reviewsCount = (int) ($row->reviews_count ?? 0);

                return [
                    'id' => $row->id,
                    'slug' => $row->slug,
                    'name' => $row->name,
                    'desc' => $row->short_description,
                    'image' => $this->toUrl($row->image_path),
                    'image_27_28' => $this->toUrl($row->image_27_28 ?? $row->image_path),
                    'image_108_53' => $this->toUrl($row->image_108_53 ?? $row->image_path),
                    'image_51_52' => $this->toUrl($row->image_51_52 ?? $row->image_path),
                    'image_99_119' => $this->toUrl($row->image_99_119 ?? $row->image_path),
                    'price' => $productPrice,
                    'sale_price' => $productSale,
                    'final_price' => $productFinal,
                    'from_variant' => $variantFrom, // "mulai dari" jika varian ada
                    'discount_percent' => $discountPct,
                    'rating_avg' => $avgRating,
                    'rating_count' => $reviewsCount,
                    'product_url' => route('catalog.show', ['slug' => $row->slug]),
                ];
            })->toArray();
        });
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
