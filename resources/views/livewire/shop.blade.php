<div class="shop-page-content mb-80" wire:loading.class="opacity-50">
    <div class="container">
        {{-- Loading indicator --}}
        <div wire:loading.flex class="position-fixed top-50 start-50 translate-middle" style="z-index: 9999;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div class="row">
            {{-- SIDEBAR --}}
            <div class="col-lg-3 order-2 order-lg-1">
                <div class="sidebar-container shop-sidebar-container">
                    {{-- Widget: Filter By --}}
                    <div class="single-sidebar-widget">
                        <h3 class="sidebar-title mb-30">Cari Berdasarkan</h3>

                        {{-- Filter Categories --}}
                        <div class="sub-widget mb-30">
                            <h3 class="sidebar-title">
                                Kategori
                                @if(count($selectedCategories) > 0)
                                    <span class="badge bg-primary ms-2">{{ count($selectedCategories) }}</span>
                                @endif
                            </h3>
                            <div class="category-container">
                                <ul>
                                    @foreach ($filterCategories as $cat)
                                        <li>
                                            <label class="checkbox-container">
                                                <input type="checkbox"
                                                       wire:model.live="selectedCategories"
                                                       value="{{ $cat->slug }}"
                                                       id="cat-{{ $cat->id }}">
                                                <span class="checkmark"></span>
                                                <span>{{ $cat->name }}</span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        {{-- Filter Brand --}}
                        <div class="sub-widget mb-30">
                            <h3 class="sidebar-title">
                                Pilih Brand ?
                                @if(count($selectedBrands) > 0)
                                    <span class="badge bg-primary ms-2">{{ count($selectedBrands) }}</span>
                                @endif
                            </h3>
                            <div class="category-container">
                                <ul>
                                    @forelse ($brands as $brand)
                                        <li>
                                            <label class="checkbox-container">
                                                <input type="checkbox"
                                                       wire:model.live="selectedBrands"
                                                       value="{{ $brand->id }}"
                                                       id="brand-{{ $brand->id }}">
                                                <span class="checkmark"></span>
                                                <span>{{ $brand->name }}</span>
                                            </label>
                                        </li>
                                    @empty
                                        <li><small class="text-muted">Tidak ada brand</small></li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>

                        {{-- Filter Price --}}
                        <div class="sub-widget mb-30">
                            <h3 class="sidebar-title">Pilih harga ?</h3>
                            <div class="category-container mb-30">
                                <ul>
                                    @foreach ($priceRanges as $key => $range)
                                        <li>
                                            <label class="radio-container">
                                                <input type="radio"
                                                       name="price-range"
                                                       wire:model.live="selectedPriceRange"
                                                       value="{{ $key }}">
                                                <span class="checkmark"></span>
                                                <span>
                                                    {{ $range['label'] }}
                                                    @if (!empty($range['count']))
                                                        ({{ $range['count'] }})
                                                    @endif
                                                </span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        {{-- Filter Size --}}
                        <div class="sub-widget">
                            <h3 class="sidebar-title">
                                Pilih ukuran ?
                                @if(count($selectedSizes) > 0)
                                    <span class="badge bg-primary ms-2">{{ count($selectedSizes) }}</span>
                                @endif
                            </h3>
                            <div class="category-container">
                                <ul>
                                    @foreach ($sizes as $size)
                                        <li>
                                            <label class="checkbox-container">
                                                <input type="checkbox"
                                                       wire:model.live="selectedSizes"
                                                       value="{{ $size['value'] }}"
                                                       id="size-{{ $size['value'] }}">
                                                <span class="checkmark"></span>
                                                <span>
                                                    {{ $size['label'] }}
                                                    @if (!empty($size['count']))
                                                        ({{ $size['count'] }})
                                                    @endif
                                                </span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- MAIN CONTENT --}}
            <div class="col-lg-9 order-1 order-lg-2">
                {{-- Shop header --}}
                <div class="shop-header mb-20">
                    {{-- Active filters badge --}}
                    @php
                        $activeFiltersCount = count($selectedCategories) + count($selectedBrands) + count($selectedSizes) + ($selectedPriceRange ? 1 : 0);
                    @endphp

                    @if($activeFiltersCount > 0)
                        <div class="alert alert-info d-flex justify-content-between align-items-center mb-3">
                            <span>
                                <strong>{{ $activeFiltersCount }}</strong> filter aktif
                            </span>
                            <button wire:click="clearFilters" class="btn btn-sm btn-outline-primary">
                                <i class="lnr lnr-cross"></i> Hapus Semua Filter
                            </button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 mb-sm-20 d-flex align-items-center">
                            {{-- View mode --}}
                            <div class="view-mode-icons">
                                <a href="#"
                                   class="{{ $viewMode === 'grid' ? 'active' : '' }}"
                                   data-target="grid"
                                   wire:click.prevent="setViewMode('grid')">
                                    <i class="fa fa-th"></i>
                                </a>
                                <a href="#"
                                   class="{{ $viewMode === 'list' ? 'active' : '' }}"
                                   data-target="list"
                                   wire:click.prevent="setViewMode('list')">
                                    <i class="fa fa-list"></i>
                                </a>
                            </div>

                            <p class="result-show-message mb-0 ms-2">
                                @if ($products->total())
                                    Tampilkan {{ $products->firstItem() }}–{{ $products->lastItem() }}
                                    dari {{ $products->total() }} hasil
                                @else
                                    Tidak ada produk ditemukan
                                @endif
                            </p>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 d-flex flex-column flex-sm-row justify-content-start justify-content-md-end align-items-sm-center">
                            {{-- Sort dropdown --}}
                            <div class="sort-by-dropdown d-flex align-items-center mb-xs-10">
                                <p class="mr-10 mb-0">Urutkan Berdasarkan: </p>
                                <select name="sort-by"
                                        id="sort-by"
                                        class="nice-select"
                                        wire:model.live="sortBy">
                                    @foreach ($sortOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Product grid/list --}}
                <div class="shop-product-wrap row {{ $viewMode }}" wire:loading.class="opacity-50">
                    @forelse ($products as $product)
                        <div class="col-lg-4 col-md-6 col-sm-6 col-12" wire:key="product-{{ $product->id }}-{{ $viewMode }}">
                            @if($viewMode === 'list')
                                <livewire:card-product-catalog-list
                                    :productId="$product->id"
                                    :key="'list-product-' . $product->id"
                                />
                            @else
                                <livewire:card-product-catalog
                                    :productId="$product->id"
                                    :key="'grid-product-' . $product->id"
                                />
                            @endif
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="lnr lnr-sad" style="font-size: 48px;"></i>
                                <p class="mb-0 mt-3">Tidak ada produk yang ditemukan dengan filter yang dipilih.</p>
                                @if($selectedCategories || $selectedBrands || $selectedPriceRange || $selectedSizes)
                                    <button wire:click="clearFilters" class="btn btn-sm btn-primary mt-3">
                                        <i class="lnr lnr-cross"></i> Hapus Semua Filter
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforelse
                </div>                {{-- Pagination --}}
                <div class="pagination-container mt-50 pb-20 mb-md-80 mb-sm-80">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 text-center text-md-start mb-sm-20">
                            <p class="show-result-text mb-0">
                                @if ($products->total())
                                    Showing {{ $products->firstItem() }}-{{ $products->lastItem() }}
                                    of {{ $products->total() }} item(s)
                                @else
                                    No item found
                                @endif
                            </p>
                        </div>

                        <div class="col-lg-8 col-md-8 col-sm-12">
                            <div class="pagination-content text-center text-md-end">
                                {{ $products->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div> {{-- /.col-lg-9 --}}
        </div>
    </div>
</div>
