<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductReview;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class TopSellingProducts extends Component
{
    /** @var array<int, array<string, mixed>> */
    public array $items = [];

    /** Banyak item top-selling yang ditampilkan */
    public int $limit = 12;

    /** Periode hari untuk hitung penjualan; null = all-time */
    public ?int $days = null;

    /** Filter kategori (opsional) */
    public ?int $categoryId = null;

    public ?string $categorySlug = null;

    public function mount(
        int $limit = 12,
        ?int $days = null,
        ?int $categoryId = null,
        ?string $categorySlug = null
    ): void {
        $this->limit = $limit;
        $this->days = $days;
        $this->categoryId = $categoryId;
        $this->categorySlug = $categorySlug;

        $this->items = $this->fetchTopSelling(
            limit: $this->limit,
            days: $this->days,
            categoryId: $this->categoryId,
            categorySlug: $this->categorySlug
        );
    }

    /**
     * Ambil produk "top-selling":
     * - Hitung total qty dari order_items (purchasable_type Product + ProductVariant->product_id), filter orders.status sukses
     * - Filter status produk 'active'
     * - Join image thumbnail/fallback, rating, min variant price
     * - Fallback ke featured terbaru jika tidak ada penjualan
     */
    protected function fetchTopSelling(int $limit, ?int $days, ?int $categoryId, ?string $categorySlug): array
    {
        $cacheKey = "topselling_v3_l{$limit}_d".($days ?? 'all').'_cid'.($categoryId ?? 'null').'_cslug'.($categorySlug ?? 'null');

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($limit, $days, $categoryId, $categorySlug) {

            // --- A. Subquery penjualan langsung ke Product
            $direct = DB::table('order_items as oi')
                ->join('orders as o', 'o.id', '=', 'oi.order_id')
                ->when($days, fn ($q) => $q->where('o.created_at', '>=', now()->subDays($days)))
                ->whereIn('o.status', ['paid', 'processing', 'shipped', 'completed'])
                ->where('oi.purchasable_type', '=', Product::class)
                ->groupBy('oi.purchasable_id')
                ->select('oi.purchasable_id as product_id', DB::raw('SUM(oi.quantity) as qty'));

            // --- B. Subquery penjualan ke ProductVariant yang digulung ke parent Product
            $variant = DB::table('order_items as oi')
                ->join('orders as o', 'o.id', '=', 'oi.order_id')
                ->join('product_variants as pv', 'pv.id', '=', 'oi.purchasable_id')
                ->when($days, fn ($q) => $q->where('o.created_at', '>=', now()->subDays($days)))
                ->whereIn('o.status', ['paid', 'processing', 'shipped', 'completed'])
                ->where('oi.purchasable_type', '=', ProductVariant::class)
                ->groupBy('pv.product_id')
                ->select('pv.product_id', DB::raw('SUM(oi.quantity) as qty'));

            // --- C. Union lalu total_qty per product
            $salesUnion = $direct->unionAll($variant);
            $totalSales = DB::query()
                ->fromSub($salesUnion, 's')
                ->select('s.product_id', DB::raw('SUM(s.qty) as total_qty'))
                ->groupBy('s.product_id');

            // --- D. Subquery min sort per product image & join thumbnail/fallback
            $minSortPerProduct = ProductImage::query()
                ->select('product_id', DB::raw('MIN(sort_order) as min_sort'))
                ->groupBy('product_id');

            // --- E. Subquery min variant price aktif untuk label "mulai dari"
            $minVariantPriceSub = ProductVariant::query()
                ->where('is_active', true)
                ->selectRaw('MIN(COALESCE(sale_price, price))')
                ->whereColumn('product_id', 'products.id');

            // --- F. Subquery rating (approved) untuk avg & count
            $avgRatingSub = ProductReview::query()
                ->where('status', 'approved')
                ->where('reviewable_type', Product::class)
                ->selectRaw('AVG(rating)')
                ->whereColumn('reviewable_id', 'products.id');

            $countRatingSub = ProductReview::query()
                ->where('status', 'approved')
                ->where('reviewable_type', Product::class)
                ->selectRaw('COUNT(*)')
                ->whereColumn('reviewable_id', 'products.id');

            // --- G. Query utama: Products yang punya sales
            $q = Product::query()
                ->joinSub($totalSales, 'ts', fn ($join) => $join->on('ts.product_id', '=', 'products.id'))
                ->leftJoin('product_images as thumb', function ($join) {
                    $join->on('thumb.product_id', '=', 'products.id')
                        ->where('thumb.is_thumbnail', '=', 1);
                })
                ->leftJoinSub($minSortPerProduct, 'ms', fn ($join) => $join->on('ms.product_id', '=', 'products.id'))
                ->leftJoin('product_images as img', function ($join) {
                    $join->on('img.product_id', '=', 'products.id')
                        ->on('img.sort_order', '=', 'ms.min_sort');
                })
                ->where('products.status', 'active')
                ->select([
                    'products.id',
                    'products.slug',
                    'products.name',
                    'products.short_description',
                    'products.price',
                    'products.sale_price',
                    DB::raw('COALESCE(thumb.path, img.path) as image_path'),
                    DB::raw('COALESCE(thumb.path_ratio_99_119, img.path_ratio_99_119) as image_99_119'),
                    DB::raw('COALESCE(products.created_at) as published_at'),
                    DB::raw('ts.total_qty as total_sold'),
                ])
                ->selectSub($minVariantPriceSub, 'from_variant_price')
                ->selectSub($avgRatingSub, 'avg_rating')
                ->selectSub($countRatingSub, 'reviews_count')
                ->orderByDesc('total_sold')
                ->orderByDesc(DB::raw('COALESCE(products.created_at)'));

            // Filter kategori (pivot product_category_product)
            if ($categoryId || $categorySlug) {
                $q->join('product_category_product as pcp', 'pcp.product_id', '=', 'products.id')
                    ->join('product_categories as pc', 'pc.id', '=', 'pcp.product_category_id');

                if ($categoryId) {
                    $q->where('pc.id', $categoryId);
                }
                if ($categorySlug) {
                    $q->where('pc.slug', $categorySlug);
                }
            }

            $rows = $q->limit($limit)->get();

            // --- H. Bila kosong (belum ada penjualan), fallback: featured aktif terbaru
            if ($rows->isEmpty()) {
                $fallback = Product::query()
                    ->leftJoin('product_images as thumb', function ($join) {
                        $join->on('thumb.product_id', '=', 'products.id')
                            ->where('thumb.is_thumbnail', '=', 1);
                    })
                    ->leftJoinSub($minSortPerProduct, 'ms', fn ($join) => $join->on('ms.product_id', '=', 'products.id'))
                    ->leftJoin('product_images as img', function ($join) {
                        $join->on('img.product_id', '=', 'products.id')
                            ->on('img.sort_order', '=', 'ms.min_sort');
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
                        DB::raw('COALESCE(thumb.path_ratio_99_119, img.path_ratio_99_119) as image_99_119'),
                        DB::raw('COALESCE(products.created_at) as published_at'),
                        DB::raw('0 as total_sold'),
                    ])
                    ->selectSub($minVariantPriceSub, 'from_variant_price')
                    ->selectSub($avgRatingSub, 'avg_rating')
                    ->selectSub($countRatingSub, 'reviews_count')
                    ->orderByDesc(DB::raw('COALESCE(products.created_at)'))
                    ->limit($limit)
                    ->get();

                $rows = $fallback;
            }

            return $rows->map(function ($row) {
                // Harga product
                $price = (float) ($row->price ?? 0);
                $sale = $row->sale_price !== null ? (float) $row->sale_price : null;
                $final = ($sale !== null && $sale > 0 && $sale < $price) ? $sale : $price;

                // Harga varian termurah (untuk label "Mulai dari")
                $fromVariant = $row->from_variant_price !== null ? (float) $row->from_variant_price : null;

                // Diskon % dari level product
                $discount = ($sale !== null && $sale > 0 && $sale < $price)
                    ? (int) round((($price - $sale) / max(1, $price)) * 100)
                    : null;

                // Rating
                $avgRating = $row->avg_rating !== null ? round((float) $row->avg_rating, 1) : null;
                $reviewsCount = (int) ($row->reviews_count ?? 0);

                return [
                    'id' => $row->id,
                    'slug' => $row->slug,
                    'name' => $row->name,
                    'desc' => $row->short_description,
                    'image' => $this->toUrl($row->image_path),
                    'image_99_119' => $this->toUrl($row->image_99_119 ?? $row->image_path),
                    'price' => $price,
                    'sale_price' => $sale,
                    'final_price' => $final,
                    'from_variant' => $fromVariant,
                    'discount' => $discount,
                    'total_sold' => (int) $row->total_sold,
                    'rating_avg' => $avgRating,
                    'rating_count' => $reviewsCount,
                    'url' => url('/product/'.$row->slug),
                ];
            })->toArray();
        });
    }

    protected function toUrl(?string $path): string
    {
        if (! $path || trim((string) $path) === '') {
            return asset('images/placeholder.jpg');
        }
        if (preg_match('~^https?://~i', $path)) {
            return $path;
        }
        try {
            return Storage::url($path);
        } catch (\Throwable $e) {
            return asset(ltrim($path, '/'));
        }
    }

    public function render()
    {
        return view('livewire.top-selling-products');
    }
}
