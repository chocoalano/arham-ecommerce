@extends('layouts.app')

@section('content')
    @livewire('breadscrumb')
    <div class="shop-page-content mb-80">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!--=======  shop header  =======-->
                    <div class="shop-header mb-20">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 mb-sm-20 d-flex align-items-center">
                                <!--=======  view mode  =======-->
                                <div class="view-mode-icons">
                                    <a class="" href="#" data-target="grid"><i class="fa fa-th"></i></a>
                                </div>
                                <p class="result-show-message">
                                    Menampilkan {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} dari
                                    {{ $products->total() }} produk
                                </p>
                                <!--=======  End of view mode  =======-->
                            </div>
                            <div
                                class="col-lg-6 col-md-6 col-sm-12 d-flex flex-column flex-sm-row justify-content-start justify-content-md-end align-items-sm-center">
                                <!--=======  Sort by dropdown  =======-->
                                <div class="sort-by-dropdown d-flex align-items-center mb-xs-10">
                                    <p class="mr-10 mb-0">Urutkan:</p>
                                    <form method="GET" action="{{ route('catalog.index') }}" id="sort-form">
                                        {{-- bawa semua query lain, tapi JANGAN bawa sort & page --}}
                                        @foreach(request()->except(['sort', 'page']) as $k => $v)
                                            @if(is_array($v))
                                                @foreach($v as $vv)
                                                    <input type="hidden" name="{{ $k }}[]" value="{{ $vv }}">
                                                @endforeach
                                            @else
                                                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                            @endif
                                        @endforeach

                                        @php $sort = $filters['sort'] ?? 'rating'; @endphp
                                        <select name="sort" id="sort-by" class="nice-select">
                                            <option value="rating" @selected($sort === 'rating')>Paling Populer</option>
                                            <option value="rating" @selected($sort === 'rating')>Rating Tertinggi</option>
                                            <option value="new" @selected($sort === 'new')>Terbaru</option>
                                            <option value="price_asc" @selected($sort === 'price_asc')>Harga: Rendah ke Tinggi
                                            </option>
                                            <option value="price_desc" @selected($sort === 'price_desc')>Harga: Tinggi ke
                                                Rendah</option>
                                        </select>

                                        <noscript>
                                            <button type="submit" class="d-none">Terapkan</button>
                                        </noscript>
                                    </form>
                                </div>
                                <!--=======  End of Sort by dropdown  =======-->
                            </div>
                        </div>
                    </div>
                    <!--=======  End of shop header  =======-->

                    <!--=======  shop product wrap   =======-->
                    <div class="shop-product-wrap row list">
                        @forelse($products as $product)
                            @php
                                $thumb = optional($product->images->firstWhere('is_thumbnail', true)) ?: $product->images->first();
                                $hasSale = !empty($product->sale_price) && $product->sale_price < $product->price;
                                $price = $hasSale ? $product->sale_price : $product->price;
                                $discountPct = $hasSale ? max(0, round((1 - ($product->sale_price / ($product->price ?: 1))) * 100)) : null;
                                $avg = round($product->reviews_avg_rating ?? 0, 1);
                                $isNew = optional($product->created_at)->gt(now()->subDays(30));
                                $productUrl = '#'; // ganti ke route('products.show', $product->slug) jika tersedia
                                $imgSrc = $thumb ? asset($thumb->path) : 'https://via.placeholder.com/300x360?text=No+Image';
                                $imgAlt = $thumb->alt_text ?? $product->name;
                            @endphp

                            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                                <!--=======  grid view product  =======-->
                                @livewire('card-product-catalog', ['productId' => $product->id], key('card-product-catalog-' . $product->id))
                                <!--=======  End of product list view  =======-->
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-warning">Produk tidak ditemukan.</div>
                            </div>
                        @endforelse
                    </div>
                    <!--=======  End of shop product wrap    =======-->

                    <!--=======  pagination  =======-->
                    <div class="pagination-container mt-50 pb-20 mb-md-80 mb-sm-80">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12 text-center text-md-start mb-sm-20">
                                <p class="show-result-text">
                                    Menampilkan {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} dari
                                    {{ $products->total() }} item
                                </p>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-12">
                                <div class="pagination-content text-center text-md-end">
                                    {{ $products->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--=======  End of pagination  =======-->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const wrap = document.querySelector('.shop-product-wrap');
            const modeBtns = document.querySelectorAll('.view-mode-icons a');

            function setMode(mode) {
                if (!wrap) return;
                wrap.classList.remove('grid', 'list');
                wrap.classList.add(mode);
                modeBtns.forEach(a => a.classList.remove('active'));
                const activeBtn = document.querySelector('.view-mode-icons a[data-target="' + mode + '"]');
                if (activeBtn) activeBtn.classList.add('active');
                try { localStorage.setItem('shop_view_mode', mode); } catch (e) { }
            }

            modeBtns.forEach(btn => btn.addEventListener('click', function (e) {
                e.preventDefault();
                setMode(this.dataset.target || 'list');
            }));

            // Default ke 'list' jika belum tersimpan
            let saved = 'list';
            try {
                saved = localStorage.getItem('shop_view_mode') || 'list';
            } catch (e) { }
            setMode(saved);

            const form = document.getElementById('sort-form');
            const select = document.getElementById('sort-by');

            function submitSort() {
                if (!form) return;
                // Pastikan param page tidak terkirim (reset ke halaman 1)
                const pageInputs = form.querySelectorAll('input[name="page"]');
                pageInputs.forEach(i => i.parentNode.removeChild(i));
                form.submit();
            }

            // 1) submit saat value select berubah
            if (select) {
                select.addEventListener('change', submitSort);
            }

            // 2) guard untuk plugin nice-select (klik pada item dropdown)
            document.addEventListener('click', function (e) {
                const opt = e.target.closest('.nice-select .option');
                if (!opt) return;
                // pastikan option ini milik form sort kita
                const wrap = opt.closest('.sort-by-dropdown');
                if (wrap && wrap.contains(form)) {
                    // beri sedikit delay agar plugin sinkronkan value select dulu
                    setTimeout(submitSort, 0);
                }
            });
        });
    </script>
@endpush
