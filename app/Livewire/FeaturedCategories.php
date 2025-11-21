<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
// use Illuminate\Support\Facades\Cache; // <-- DIHAPUS
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class FeaturedCategories extends Component
{
    /** @var array<int, array<string, mixed>> */
    public array $categories = [];

    /** Batas jumlah kategori ditampilkan */
    public int $limit = 4;

    /** Tampilkan hanya root categories (parent_id = null) */
    public bool $onlyRoot = true;

    /** Sembunyikan kategori tanpa produk aktif */
    public bool $hideEmpty = true;

    public function mount(int $limit = 4, bool $onlyRoot = true, bool $hideEmpty = true): void
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

        // -- Subquery: path fallback image per kategori (with all ratios)
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
                DB::raw('COALESCE(thumb.path_ratio_27_28, img.path_ratio_27_28) as prod_image_27_28'),
                DB::raw('COALESCE(thumb.path_ratio_108_53, img.path_ratio_108_53) as prod_image_108_53'),
                DB::raw('COALESCE(thumb.path_ratio_51_52, img.path_ratio_51_52) as prod_image_51_52'),
                DB::raw('COALESCE(thumb.path_ratio_99_119, img.path_ratio_99_119) as prod_image_99_119'),
            ]);

        // -- Query utama: Eloquent ProductCategory + join fallback image
        $q = ProductCategory::query()
            ->where('product_categories.is_active', true)
            ->where('product_categories.highlight', true)
            ->when($onlyRoot, fn ($qq) => $qq->whereNull('product_categories.parent_id'))
            ->leftJoinSub($fallbackImagePerCategory, 'fc', function ($join) {
                $join->on('fc.product_category_id', '=', 'product_categories.id');
            })
            ->select([
                'product_categories.id',
                'product_categories.slug',
                'product_categories.name',
                'product_categories.image_path',
                DB::raw('COALESCE(product_categories.image_path, fc.prod_image_path) as effective_image_path'),
                'fc.prod_image_27_28',
                'fc.prod_image_108_53',
                'fc.prod_image_51_52',
                'fc.prod_image_99_119',
            ])
            ->withCount([
                'products as products_active_count' => function ($qq) {
                    $qq->where('status', 'active');
                },
            ])
            ->orderByDesc('products_active_count')
            ->orderBy('product_categories.name');

        $rows = $q->get();

        // Filter empty categories in PHP for SQLite compatibility
        if ($hideEmpty) {
            $rows = $rows->filter(fn ($row) => $row->products_active_count > 0);
        }

        // Apply limit after filtering
        $rows = $rows->take($limit);

        return $rows->map(function ($row) {
            return [
                'id' => $row->id,
                'slug' => $row->slug,
                'name' => $row->name,
                'count' => (int) ($row->products_active_count ?? 0),
                'image' => $this->toUrl($row->effective_image_path),
                'image_27_28' => $this->toUrl($row->prod_image_27_28 ?? $row->effective_image_path),
                'image_108_53' => $this->toUrl($row->prod_image_108_53 ?? $row->effective_image_path),
                'image_51_52' => $this->toUrl($row->prod_image_51_52 ?? $row->effective_image_path),
                'image_99_119' => $this->toUrl($row->prod_image_99_119 ?? $row->effective_image_path),
                'url' => route('catalog.index', ['slug' => $row->slug]),
            ];
        })->toArray();
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
