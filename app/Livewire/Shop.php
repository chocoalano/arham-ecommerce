<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class Shop extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $viewMode = 'grid';

    public string $sortBy = 'popularity';

    public int $perPage = 12;

    public int $page = 1;

    // FILTER STATE
    public array $selectedCategories = [];   // pakai slug category

    public array $selectedBrands = [];

    public ?string $selectedPriceRange = null; // ex: "11-12", "70-80", "70+"

    public array $selectedSizes = [];   // ex: ["S","M"]

    protected $queryString = [
        'viewMode' => ['except' => 'grid', 'as' => 'view'],
        'sortBy' => ['except' => 'popularity', 'as' => 'sort'],
        'selectedCategories' => ['except' => [], 'as' => 'cat'],
        'selectedBrands' => ['except' => [], 'as' => 'brand'],
        'selectedPriceRange' => ['except' => null, 'as' => 'price'],
        'selectedSizes' => ['except' => [], 'as' => 'size'],
        'page' => ['except' => 1],
    ];

    public function mount(): void
    {
        // Initialize from query string if available
        // This ensures filters are properly loaded from URL
    }

    public function updating($name, $value): void
    {
        // Kalau filter berubah -> reset ke page 1
        if (in_array($name, [
            'selectedCategories',
            'selectedBrands',
            'selectedPriceRange',
            'selectedSizes',
            'sortBy',
        ], true)) {
            $this->resetPage();
        }
    }

    public function updatedSelectedCategories(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedBrands(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedPriceRange(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedSizes(): void
    {
        $this->resetPage();
    }

    public function updatedSortBy(): void
    {
        $this->resetPage();
    }

    public function setViewMode(string $mode): void
    {
        $this->viewMode = in_array($mode, ['grid', 'list'], true) ? $mode : 'grid';
    }

    /**
     * Klik kategori di sidebar (tree "Sofas & Chairs")
     * akan meng-set filter category berdasarkan slug.
     */
    public function filterByCategory(int $categoryId): void
    {
        $category = ProductCategory::find($categoryId);

        if ($category) {
            // Toggle kategori jika sudah dipilih
            if (in_array($category->slug, $this->selectedCategories, true)) {
                $this->selectedCategories = array_values(
                    array_diff($this->selectedCategories, [$category->slug])
                );
            } else {
                $this->selectedCategories[] = $category->slug;
            }

            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->selectedCategories = [];
        $this->selectedBrands = [];
        $this->selectedPriceRange = null;
        $this->selectedSizes = [];
        $this->resetPage();

        // Dispatch event untuk refresh UI
        $this->dispatch('filtersCleared');
    }

    public function toggleCategory(string $slug): void
    {
        if (in_array($slug, $this->selectedCategories, true)) {
            $this->selectedCategories = array_values(
                array_diff($this->selectedCategories, [$slug])
            );
        } else {
            $this->selectedCategories[] = $slug;
        }
        $this->resetPage();
    }

    public function toggleBrand(int $brandId): void
    {
        if (in_array($brandId, $this->selectedBrands, true)) {
            $this->selectedBrands = array_values(
                array_diff($this->selectedBrands, [$brandId])
            );
        } else {
            $this->selectedBrands[] = $brandId;
        }
        $this->resetPage();
    }

    public function toggleSize(string $size): void
    {
        if (in_array($size, $this->selectedSizes, true)) {
            $this->selectedSizes = array_values(
                array_diff($this->selectedSizes, [$size])
            );
        } else {
            $this->selectedSizes[] = $size;
        }
        $this->resetPage();
    }

    protected function parsePriceRange(?string $key): array
    {
        if (! $key) {
            return [null, null];
        }

        // "70+" -> [70, null]
        if (Str::endsWith($key, '+')) {
            $min = (float) Str::before($key, '+');

            return [$min, null];
        }

        // "11-12" -> [11,12]
        if (Str::contains($key, '-')) {
            [$min, $max] = explode('-', $key, 2);

            return [(float) $min, (float) $max];
        }

        return [null, null];
    }

    protected function baseQuery()
    {
        $query = Product::query()
            ->with(['images' => function ($q) {
                $q->orderBy('sort_order');
            }, 'variants' => function ($q) {
                $q->where('is_active', true);
            }, 'categories', 'brand'])
            ->where('status', 'active');

        /**
         * FILTER: CATEGORY (slug dari checkbox & sidebar)
         */
        if (! empty($this->selectedCategories)) {
            $selected = $this->selectedCategories;

            $query->whereHas('categories', function ($q) use ($selected) {
                $q->where(function ($sub) use ($selected) {
                    $sub->whereIn('product_categories.slug', $selected)
                        ->orWhereIn('product_categories.id', $selected);
                });
            });
        }

        /**
         * FILTER: BRAND (relasi brand_id)
         */
        if (! empty($this->selectedBrands)) {
            $query->whereIn('brand_id', $this->selectedBrands);
        }

        /**
         * FILTER: PRICE (pakai ProductVariant: price + sale_price)
         */
        if ($this->selectedPriceRange) {
            [$min, $max] = $this->parsePriceRange($this->selectedPriceRange);

            $query->whereHas('variants', function ($q) use ($min, $max) {
                $q->where('is_active', true);

                if (! is_null($min)) {
                    $q->where(function ($sub) use ($min) {
                        $sub->whereNotNull('sale_price')
                            ->where('sale_price', '>=', $min)
                            ->orWhere(function ($sub2) use ($min) {
                                $sub2->whereNull('sale_price')
                                    ->where('price', '>=', $min);
                            });
                    });
                }

                if (! is_null($max)) {
                    $q->where(function ($sub) use ($max) {
                        $sub->whereNotNull('sale_price')
                            ->where('sale_price', '<=', $max)
                            ->orWhere(function ($sub2) use ($max) {
                                $sub2->whereNull('sale_price')
                                    ->where('price', '<=', $max);
                            });
                    });
                }
            });
        }

        /**
         * FILTER: SIZE (options->size di ProductVariant)
         */
        if (! empty($this->selectedSizes)) {
            $sizes = $this->selectedSizes;

            $query->whereHas('variants', function ($q) use ($sizes) {
                $q->where('is_active', true)
                    ->where(function ($sub) use ($sizes) {
                        foreach ($sizes as $size) {
                            $sub->orWhere('options->size', $size);
                        }
                    });
            });
        }

        /**
         * MIN PRICE PER PRODUCT (untuk sorting price_asc / price_desc)
         * Menggunakan COALESCE untuk memilih sale_price jika ada, atau price
         */
        $query->withMin('variants as min_variant_price', 'price');

        /**
         * SORTING
         */
        switch ($this->sortBy) {
            case 'price_asc':
                $query->orderBy('min_variant_price');
                break;

            case 'price_desc':
                $query->orderByDesc('min_variant_price');
                break;

            case 'newness':
                $query->orderByDesc('created_at');
                break;

            case 'rating':
                // Order by average rating if reviews relationship exists
                $query->orderByDesc('created_at'); // fallback
                break;

            case 'popularity':
            default:
                // Could be based on sales count, views, or fallback to newest
                $query->orderByDesc('created_at');
                break;
        }

        return $query;
    }

    public function getProductsProperty()
    {
        return $this->baseQuery()->paginate($this->perPage);
    }

    public function render()
    {
        // Kategori root untuk sidebar "Sofas & Chairs"
        $rootCategories = ProductCategory::query()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->with(['children' => function ($q) {
                $q->where('is_active', true)->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        // Kategori untuk filter "Categories"
        $filterCategories = ProductCategory::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Brand dari tabel brands
        $brands = Brand::query()
            ->whereHas('products', function ($q) {
                $q->where('status', 'active');
            })
            ->orderBy('name')
            ->get();

        // Price range dengan format Rupiah
        $priceRanges = [
            '0-100000' => [
                'label' => 'Di bawah Rp 100.000',
                'count' => null,
            ],
            '100000-500000' => [
                'label' => 'Rp 100.000 - Rp 500.000',
                'count' => null,
            ],
            '500000-1000000' => [
                'label' => 'Rp 500.000 - Rp 1.000.000',
                'count' => null,
            ],
            '1000000-5000000' => [
                'label' => 'Rp 1.000.000 - Rp 5.000.000',
                'count' => null,
            ],
            '5000000+' => [
                'label' => 'Di atas Rp 5.000.000',
                'count' => null,
            ],
        ];

        // Size static – pastikan ProductVariant.options->size pakai value ini
        $sizes = [
            ['value' => 'S',  'label' => 'S',  'count' => null],
            ['value' => 'M',  'label' => 'M',  'count' => null],
            ['value' => 'L',  'label' => 'L',  'count' => null],
            ['value' => 'XL', 'label' => 'XL', 'count' => null],
        ];

        $sortOptions = [
            'popularity' => 'Paling Populer',
            'rating' => 'Rating Tertinggi',
            'newness' => 'Terbaru',
            'price_asc' => 'Harga: Rendah ke Tinggi',
            'price_desc' => 'Harga: Tinggi ke Rendah',
        ];

        return view('livewire.shop', [
            'products' => $this->products,
            'rootCategories' => $rootCategories,
            'filterCategories' => $filterCategories,
            'brands' => $brands,
            'priceRanges' => $priceRanges,
            'sizes' => $sizes,
            'sortOptions' => $sortOptions,
        ]);
    }
}
