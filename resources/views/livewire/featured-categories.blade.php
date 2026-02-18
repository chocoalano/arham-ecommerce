@php
    $cats = collect($categories ?? [])->take(4)->values();

    $c0 = $cats->get(0);
    $c1 = $cats->get(1);
    $c2 = $cats->get(2);
    $c3 = $cats->get(3);

    $fallback = asset('assets/img/placeholder/category.jpg'); // sesuaikan
    $img = function ($c, $key) use ($fallback) {
        if (!$c)
            return $fallback;
        $path = $c[$key] ?? null;
        return !empty($path) ? asset($path) : $fallback;
    };
@endphp

<div class="featured-categories fc m-b-80">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center m-b-40">
                <div class="section-title">
                    <h2><span>Kategori</span> Pilihan</h2>
                    <p>Tampilkan semua kategori pilihan beserta produk di halaman utama.</p>
                </div>
            </div>
        </div>

        @if($cats->count() > 0)
            <div class="row fc-grid">
                {{-- LEFT BIG --}}
                <div class="col-md-6 fc-col fc-left">
                    @if($c0)
                        <div class="fc-banner fc-banner--lg" wire:key="cat-{{ $c0['id'] }}">
                            <a href="{{ $c0['url'] }}" class="fc-link">
                                <div class="fc-media">
                                    <img src="{{ $img($c0, 'image_27_28') }}" alt="{{ $c0['name'] }}"
                                        class="img-responsive fc-img" loading="lazy"
                                        onerror="this.onerror=null;this.src='{{ $fallback }}';">
                                </div>

                                <div class="fc-overlay"></div>

                                <div class="fc-title">
                                    <div class="fc-kicker">Featured</div>
                                    <div class="fc-name">{{ $c0['name'] }}</div>
                                </div>
                            </a>
                        </div>
                    @endif
                </div>

                {{-- RIGHT STACK --}}
                <div class="col-md-6 fc-col fc-right">
                    {{-- TOP WIDE --}}
                    @if($c1)
                        <div class="fc-banner fc-banner--wide" wire:key="cat-{{ $c1['id'] }}">
                            <a href="{{ $c1['url'] }}" class="fc-link">
                                <div class="fc-media">
                                    <img src="{{ $img($c1, 'image_108_53') }}" alt="{{ $c1['name'] }}"
                                        class="img-responsive fc-img" loading="lazy"
                                        onerror="this.onerror=null;this.src='{{ $fallback }}';">
                                </div>

                                <div class="fc-overlay"></div>

                                <div class="fc-title">
                                    <div class="fc-kicker">Top pick</div>
                                    <div class="fc-name">{{ $c1['name'] }}</div>
                                </div>
                            </a>
                        </div>
                    @endif

                    {{-- BOTTOM TWO (HARUS PRESISI SEJAJAR) --}}
                    <div class="fc-bottom">
                        @if($c2)
                            <div class="fc-cell" wire:key="cat-{{ $c2['id'] }}">
                                <div class="fc-banner fc-banner--bottom">
                                    <a href="{{ $c2['url'] }}" class="fc-link">
                                        <div class="fc-media">
                                            <img src="{{ $img($c2, 'image_51_52') }}" alt="{{ $c2['name'] }}"
                                                class="img-responsive fc-img" loading="lazy"
                                                onerror="this.onerror=null;this.src='{{ $fallback }}';">
                                        </div>

                                        <div class="fc-overlay"></div>

                                        <div class="fc-title">
                                            <div class="fc-kicker">Hot</div>
                                            <div class="fc-name">{{ $c2['name'] }}</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($c3)
                            <div class="fc-cell" wire:key="cat-{{ $c3['id'] }}">
                                <div class="fc-banner fc-banner--bottom">
                                    <a href="{{ $c3['url'] }}" class="fc-link">
                                        <div class="fc-media">
                                            <img src="{{ $img($c3, 'image_99_119') }}" alt="{{ $c3['name'] }}"
                                                class="img-responsive fc-img" loading="lazy"
                                                onerror="this.onerror=null;this.src='{{ $fallback }}';">
                                        </div>

                                        <div class="fc-overlay"></div>

                                        <div class="fc-title">
                                            <div class="fc-kicker">Trending</div>
                                            <div class="fc-name">{{ $c3['name'] }}</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        @else
            <div class="row">
                <div class="col-xs-12">
                    <div class="alert alert-info text-center m-b-0">
                        Belum ada kategori aktif yang memiliki produk.
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>