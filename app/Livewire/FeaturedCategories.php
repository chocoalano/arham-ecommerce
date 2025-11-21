<?php

namespace App\Livewire;

use App\Models\ProductCategory;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class FeaturedCategories extends Component
{
    /** @var array<int, array<string, mixed>> */
    public array $categories = [];

    /** Batas jumlah kategori ditampilkan */
    public int $limit = 4;

    public function mount(int $limit = 4): void
    {
        $this->limit = max(1, min(20, (int) $limit));
        $this->categories = $this->fetchCategories($this->limit);
    }

    /**
     * Ambil kategori yang highlight = true saja
     * Hanya menggunakan image_path dari kategori, tanpa fallback dari product
     */
    protected function fetchCategories(int $limit): array
    {
        $rows = ProductCategory::query()
            ->where('highlight', true)
            ->select([
                'id',
                'slug',
                'name',
                'image_path',
                'sort_order',
            ])
            ->withCount([
                'products as products_active_count' => function ($qq) {
                    $qq->where('status', 'active');
                },
            ])
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->limit($limit)
            ->get();

        return $rows->map(function ($row) {
            return [
                'id' => $row->id,
                'slug' => $row->slug ?? '',
                'name' => $row->name ?? '',
                'count' => (int) ($row->products_active_count ?? 0),
                'image' => $this->toUrl($row->image_path),
                'image_27_28' => $this->toUrl($row->image_path),
                'image_108_53' => $this->toUrl($row->image_path),
                'image_51_52' => $this->toUrl($row->image_path),
                'image_99_119' => $this->toUrl($row->image_path),
                'url' => route('catalog.index', ['slug' => $row->slug ?? '']),
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
