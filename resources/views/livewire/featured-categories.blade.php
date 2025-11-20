{{-- Featured Categories (dinamis, anti N+1, dengan fallback image) --}}
<div class="featured-categories mb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center mb-40">
                <div class="section-title">
                    <h2><span>Kategori</span> Pilihan</h2>
                    <p>Tampilkan semua kategori pilihan beserta produk di halaman utama.</p>
                </div>
            </div>
        </div>
        @if(count($categories) > 0)
            <div class="row">
                {{-- Large banner on left (First category) - Ratio 27:28 (540×560) --}}
                @if(isset($categories[0]))
                    <div class="col-lg-6 col-md-6 mb-sm-30" wire:key="cat-{{ $categories[0]['id'] }}">
                        <div class="banner">
                            <a href="{{ $categories[0]['url'] }}">
                                <img width="540" height="560" src="{{ $categories[0]['image_27_28'] }}" class="img-fluid"
                                    alt="{{ $categories[0]['name'] }}" loading="lazy">
                            </a>
                            <span class="banner-category-title">
                                <a href="{{ $categories[0]['url'] }}">{{ $categories[0]['name'] }}</a>
                            </span>
                        </div>
                    </div>
                @endif

                {{-- Right side with 3 banners --}}
                <div class="col-lg-6 col-md-6">
                    <div class="row">
                        {{-- Wide banner (Second category) - Ratio 108:53 (540×265) --}}
                        @if(isset($categories[1]))
                            <div class="col-lg-12 col-md-12 mb-30" wire:key="cat-{{ $categories[1]['id'] }}">
                                <div class="banner">
                                    <a href="{{ $categories[1]['url'] }}">
                                        <img width="550" height="270" src="{{ $categories[1]['image_108_53'] }}"
                                            class="img-fluid" alt="{{ $categories[1]['name'] }}" loading="lazy">
                                    </a>
                                    <span class="banner-category-title">
                                        <a href="{{ $categories[1]['url'] }}">{{ $categories[1]['name'] }}</a>
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        {{-- Small square banner left (Third category) - Ratio 51:52 (255×260) --}}
                        @if(isset($categories[2]))
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6" wire:key="cat-{{ $categories[2]['id'] }}">
                                <div class="banner">
                                    <a href="{{ $categories[2]['url'] }}">
                                        <img width="265" height="270" src="{{ $categories[2]['image_51_52'] }}"
                                            class="img-fluid" alt="{{ $categories[2]['name'] }}" loading="lazy">
                                    </a>
                                    <span class="banner-category-title">
                                        <a href="{{ $categories[2]['url'] }}">{{ $categories[2]['name'] }}</a>
                                    </span>
                                </div>
                            </div>
                        @endif
                        {{-- Small portrait banner right (Fourth category) - Ratio 99:119 (198×238) --}}
                        @if(isset($categories[3]))
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6" wire:key="cat-{{ $categories[3]['id'] }}">
                                <div class="banner">
                                    <a href="{{ $categories[3]['url'] }}">
                                        <img width="265" height="270" src="{{ $categories[3]['image_99_119'] }}"
                                            class="img-fluid" alt="{{ $categories[3]['name'] }}" loading="lazy">
                                    </a>
                                    <span class="banner-category-title">
                                        <a href="{{ $categories[3]['url'] }}">{{ $categories[3]['name'] }}</a>
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info mb-0 text-center">
                        Belum ada kategori aktif yang memiliki produk.
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
