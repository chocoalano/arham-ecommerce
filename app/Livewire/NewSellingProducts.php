<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Throwable;

class NewSellingProducts extends Component
{
    /** @var array<int, array{id:int}> */
    public array $items = [];

    /** Banyak item rekomendasi terbaru yang ditampilkan */
    public int $limit = 6;

    /** Filter kategori (opsional) */
    public ?int $categoryId = null;

    public ?string $categorySlug = null;

    public function mount(
        int $limit = 6,
        ?int $categoryId = null,
        ?string $categorySlug = null
    ): void {
        $this->limit = max(1, min(6, $limit));
        $this->categoryId = $categoryId;
        $this->categorySlug = $categorySlug;

        try {
            $this->items = $this->fetchLatestRecommendations(
                limit: $this->limit,
                categoryId: $this->categoryId,
                categorySlug: $this->categorySlug,
            );
        } catch (Throwable $e) {
            logger()->error('NewSellingProducts fetch error: '.$e->getMessage(), [
                'limit' => $this->limit,
                'categoryId' => $this->categoryId,
                'categorySlug' => $this->categorySlug,
            ]);

            $this->items = [];
        }
    }

    /**
     * Ambil rekomendasi produk terbaru:
     * - Produk active dan belum dihapus
     * - Urutkan berdasarkan created_at terbaru
     * - Opsional filter kategori
     */
    protected function fetchLatestRecommendations(int $limit, ?int $categoryId, ?string $categorySlug): array
    {
        $query = Product::query()
            ->where('products.status', 'active')
            ->whereNull('products.deleted_at');

        if ($categoryId || $categorySlug) {
            $query->join('product_category_product as pcp', 'pcp.product_id', '=', 'products.id')
                ->join('product_categories as pc', 'pc.id', '=', 'pcp.product_category_id');

            if ($categoryId) {
                $query->where('pc.id', (int) $categoryId);
            }

            if ($categorySlug) {
                $query->where('pc.slug', $categorySlug);
            }
        }

        $rows = $query
            ->select('products.id', 'products.created_at')
            ->distinct()
            ->orderByDesc('products.created_at')
            ->limit($limit)
            ->get();

        if ($rows->isEmpty() && ($categoryId || $categorySlug)) {
            // Jika filter kategori tidak punya data, fallback ke produk aktif terbaru tanpa kategori.
            $rows = Product::query()
                ->where('products.status', 'active')
                ->whereNull('products.deleted_at')
                ->select('products.id', 'products.created_at')
                ->orderByDesc('products.created_at')
                ->limit($limit)
                ->get();
        }

        return $rows
            ->map(fn ($row): array => ['id' => (int) $row->id])
            ->values()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.new-selling-products');
    }
}
