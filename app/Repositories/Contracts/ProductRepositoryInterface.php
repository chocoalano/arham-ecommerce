<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    /**
     * Return paginated catalog products with filters + sorting applied.
     *
     * Supported $filters keys:
     * - q: string (search in name/sku/description)
     * - category: int|slug
     * - price_min: float
     * - price_max: float
     * - in_stock: bool
     * - has_discount: bool
     * - sort: string [rating|new|price_asc|price_desc|name_asc|name_desc]
     * - per_page: int
     */
    public function catalog(array $filters = []): LengthAwarePaginator;

    /**
     * All categories with product counts (for sidebar filter).
     */
    public function categoriesWithCounts(): Collection;

    /**
     * Min / Max effective price across products (sale_price fallback to price).
     * Returns: ['min' => float, 'max' => float]
     */
    public function priceRange(?array $filters = []): array;

    public function findBySlug(string $slug): ?Product;
}
