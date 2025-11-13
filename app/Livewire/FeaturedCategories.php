<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Cache;
// Models
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class FeaturedCategories extends Component
{
    /** @var array<int, array<string, mixed>> */
    public array $categories = [];

    /** Batas jumlah kategori ditampilkan */
    public int $limit = 6;

    /** Tampilkan hanya root categories (parent_id = null) */
    public bool $onlyRoot = true;

    /** Sembunyikan kategori tanpa produk aktif */
    public bool $hideEmpty = true;

    public function mount(int $limit = 6, bool $onlyRoot = true, bool $hideEmpty = true): void
    {
        $this->limit = $limit;
        $this->onlyRoot = $onlyRoot;
        $this->hideEmpty = $hideEmpty;

        $this->categories = $this->fetchCategories(
            $this->limit, $this->onlyRoot, $this->hideEmpty
        );
    }

    protected function fetchCategories(int $limit, bool $onlyRoot, bool $hideEmpty): array
    {
        $cacheKey = "featured_categories_v3_{$limit}_".($onlyRoot ? 'root' : 'all').'_'.($hideEmpty ? 'hide0' : 'show0');

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($limit, $onlyRoot, $hideEmpty) {
            // -- Subquery: ambil 1 produk aktif per kategori (untuk fallback image)
            $minActiveProductPerCategory = DB::table('product_category_product as pcp')
                ->join('products as p', 'p.id', '=', 'pcp.product_id')
                ->where('p.status', '=', 'active')
                ->select('pcp.product_category_id', DB::raw('MIN(pcp.product_id) as min_product_id'))
                ->groupBy('pcp.product_category_id');

            // -- Subquery: ambil min sort_order per product untuk fallback image
            $minSortPerProduct = ProductImage::query()
                ->select('product_id', DB::raw('MIN(sort_order) as min_sort'))
                ->groupBy('product_id');

            // -- Subquery: path fallback image per kategori
            $fallbackImagePerCategory = DB::query()
                ->fromSub($minActiveProductPerCategory, 'mp')
                ->leftJoin('product_images as thumb', function ($join) {
                    $join->on('thumb.product_id', '=', 'mp.min_product_id')
                        ->where('thumb.is_thumbnail', '=', 1);
                })
                ->leftJoinSub($minSortPerProduct, 'ms', function ($join) {
                    $join->on('ms.product_id', '=', 'mp.min_product_id');
                })
                ->leftJoin('product_images as img', function ($join) {
                    $join->on('img.product_id', '=', 'mp.min_product_id')
                        ->on('img.sort_order', '=', 'ms.min_sort');
                })
                ->select([
                    'mp.product_category_id',
                    DB::raw('COALESCE(thumb.path, img.path) as prod_image_path'),
                ]);

            // -- Query utama: Eloquent ProductCategory + join fallback image
            $q = ProductCategory::query()
                ->where('product_categories.is_active', true)
                ->when($onlyRoot, fn ($qq) => $qq->whereNull('product_categories.parent_id'))
                ->leftJoinSub($fallbackImagePerCategory, 'fc', function ($join) {
                    $join->on('fc.product_category_id', '=', 'product_categories.id');
                })
                // PENTING: select dulu...
                ->select([
                    'product_categories.id',
                    'product_categories.slug',
                    'product_categories.name',
                    'product_categories.image_path',
                    DB::raw('COALESCE(product_categories.image_path, fc.prod_image_path) as effective_image_path'),
                ])
                // ...baru withCount supaya alias 'products_active_count' IKUT terseleksi
                ->withCount([
                    'products as products_active_count' => function ($qq) {
                        $qq->where('status', 'active');
                    },
                ]);

            // Filter yang kosong pakai HAVING (alias siap karena withCount dipanggil SETELAH select)
            if ($hideEmpty) {
                $q->having('products_active_count', '>', 0);
            }

            // Urutkan populer → nama
            $q->orderByDesc('products_active_count')
                ->orderBy('product_categories.name');

            $rows = $q->limit($limit)->get();

            return $rows->map(function ($row) {
                return [
                    'id' => $row->id,
                    'slug' => $row->slug,
                    'name' => $row->name,
                    'count' => (int) ($row->products_active_count ?? 0),
                    'image' => $this->toUrl($row->effective_image_path),
                    'url' => url('/category/'.$row->slug), // ganti ke route() jika punya route name
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
        return view('livewire.featured-categories');
    }
}
