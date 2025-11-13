<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function catalog(array $filters = []): LengthAwarePaginator
    {
        $perPage = (int) ($filters['per_page'] ?? 12);
        if ($perPage < 1 || $perPage > 100) {
            $perPage = 12;
        }

        $query = Product::query()
            ->with([
                'images' => function ($q) {
                    $q->orderByDesc('is_thumbnail')->orderBy('sort_order');
                },
                'variants',
                'categories',
            ])
            ->withAvg('reviews as reviews_avg_rating', 'rating')
            ->withCount('reviews');

        // Search
        if (! empty($filters['q'])) {
            $q = trim($filters['q']);
            $query->where(function (Builder $qq) use ($q) {
                $qq->where('name', 'LIKE', "%{$q}%")
                    ->orWhere('slug', 'LIKE', "%{$q}%")
                    ->orWhere('sku', 'LIKE', "%{$q}%")
                    ->orWhere('short_description', 'LIKE', "%{$q}%")
                    ->orWhere('description', 'LIKE', "%{$q}%");
            });
        }

        // Category filter (accept id or slug)
        if (! empty($filters['category'])) {
            $cat = $filters['category'];
            $query->whereHas('categories', function (Builder $q) use ($cat) {
                if (is_numeric($cat)) {
                    $q->where('product_categories.id', $cat);
                } else {
                    $q->where('product_categories.slug', $cat);
                }
            });
        }

        // Stock filter
        if (! empty($filters['in_stock'])) {
            $query->where(function (Builder $q) {
                $q->where('stock', '>', 0)
                    ->orWhereHas('variants', function (Builder $v) {
                        $v->where('stock', '>', 0);
                    });
            });
        }

        // Discount filter (sale price exists at product OR variant level)
        if (! empty($filters['has_discount'])) {
            $query->where(function (Builder $q) {
                $q->whereNotNull('sale_price')
                    ->orWhere(function (Builder $qq) {
                        $qq->whereHas('variants', function (Builder $v) {
                            $v->whereNotNull('sale_price');
                        });
                    });
            });
        }

        // Price filter using COALESCE(sale_price, price)
        $priceCol = 'COALESCE(products.sale_price, products.price)';
        if (isset($filters['price_min']) && is_numeric($filters['price_min'])) {
            $query->whereRaw("{$priceCol} >= ?", [(float) $filters['price_min']]);
        }
        if (isset($filters['price_max']) && is_numeric($filters['price_max'])) {
            $query->whereRaw("{$priceCol} <= ?", [(float) $filters['price_max']]);
        }

        // Sorting
        switch ($filters['sort'] ?? null) {
            case 'rating':
                $query->orderByDesc('reviews_avg_rating')->orderByDesc('reviews_count');
                break;
            case 'new':
                $query->orderByDesc('created_at');
                break;
            case 'price_asc':
                $query->orderByRaw("{$priceCol} ASC NULLS LAST")
                    ->orderBy('name');
                break;
            case 'price_desc':
                $query->orderByRaw("{$priceCol} DESC NULLS LAST")
                    ->orderBy('name');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'name_asc':
            default:
                $query->orderBy('name');
                break;
        }

        return $query->paginate($perPage)->appends($this->persistedQueryParams($filters));
    }

    public function categoriesWithCounts(): Collection
    {
        return ProductCategory::query()
            ->withCount(['products'])
            ->orderBy('name')
            ->get();
    }

    public function priceRange(?array $filters = []): array
    {
        $q = Product::query();
        // carry only restrictive filters that affect price domain meaningfully
        if (! empty($filters['category'])) {
            $cat = $filters['category'];
            $q->whereHas('categories', function (Builder $b) use ($cat) {
                if (is_numeric($cat)) {
                    $b->where('product_categories.id', $cat);
                } else {
                    $b->where('product_categories.slug', $cat);
                }
            });
        }

        $priceCol = 'COALESCE(products.sale_price, products.price)';
        $min = (clone $q)->whereNotNull('price')->min(\DB::raw($priceCol));
        $max = (clone $q)->whereNotNull('price')->max(\DB::raw($priceCol));

        return [
            'min' => (float) ($min ?? 0),
            'max' => (float) ($max ?? 0),
        ];
    }

    private function persistedQueryParams(array $filters): array
    {
        // Keep only safe params to append to pagination
        return array_filter([
            'q' => $filters['q'] ?? null,
            'category' => $filters['category'] ?? null,
            'price_min' => $filters['price_min'] ?? null,
            'price_max' => $filters['price_max'] ?? null,
            'in_stock' => ! empty($filters['in_stock']) ? 1 : null,
            'has_discount' => ! empty($filters['has_discount']) ? 1 : null,
            'sort' => $filters['sort'] ?? null,
            'per_page' => $filters['per_page'] ?? null,
        ], function ($v) {
            return ! is_null($v) && $v !== '';
        });
    }

    public function findBySlug(string $slug): ?Product
    {
        return Product::where('slug', $slug)
            ->with([
                'brand',
                'images' => function ($q) {
                    $q->orderByDesc('is_thumbnail')->orderBy('sort_order');
                },
                'variants',
                'categories',
            ])
            ->withAvg('reviews as reviews_avg_rating', 'rating')
            ->withCount('reviews')
            ->first();
    }
}
