{{-- resources/views/livewire/top-selling-products.blade.php --}}
@php
    $items = collect($items ?? [])->values();
    $count = $items->count();
    $maxDesktop = 6;
    $showDesktop = min($maxDesktop, max(1, $count));
@endphp

<div class="top-selling-products m-b-80">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center m-b-40">
                <div class="section-title">
                    <h2><span>Koleksi</span> Terbaru</h2>
                    <p>Jelajahi koleksi produk baru kami, Anda pasti akan menemukan apa yang Anda cari.</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                @if($count > $maxDesktop)
                    {{-- Slider mode: lebih dari 6 item --}}
                    <div class="ptk-slider top-selling-slider" data-rows="1" data-count="{{ $count }}"
                        data-show="{{ $showDesktop }}" @if(!app()->runningUnitTests()) wire:ignore @endif>
                        @foreach($items as $p)
                            <div class="ts-slide" wire:key="topselling-{{ $p['id'] }}">
                                <livewire:card-product-catalog :productId="$p['id']" :wire:key="'card-product-catalog-' . $p['id']" />
                            </div>
                        @endforeach
                    </div>
                @elseif($count > 0)
                    {{-- Grid mode: 1-6 item --}}
                    <div class="top-selling-grid ts-grid-{{ $count }}">
                        @foreach($items as $p)
                            <div class="ts-grid-item" wire:key="topselling-{{ $p['id'] }}">
                                <livewire:card-product-catalog :productId="$p['id']" :wire:key="'card-product-catalog-' . $p['id']" />
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="top-selling-empty text-center">
                        <p>Belum ada produk tersedia saat ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .m-b-0 {
            margin-bottom: 0 !important;
        }

        .m-b-40 {
            margin-bottom: 40px;
        }

        .m-b-80 {
            margin-bottom: 80px;
        }

        /* ===== Grid mode (1-6 items) ===== */
        .top-selling-grid {
            display: grid;
            gap: 20px;
            justify-items: center;
        }

        /* Desktop: tampilkan sesuai jumlah item, max 6 kolom */
        .ts-grid-1 { grid-template-columns: minmax(0, 280px); justify-content: center; }
        .ts-grid-2 { grid-template-columns: repeat(2, minmax(0, 280px)); justify-content: center; }
        .ts-grid-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .ts-grid-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .ts-grid-5 { grid-template-columns: repeat(5, minmax(0, 1fr)); }
        .ts-grid-6 { grid-template-columns: repeat(6, minmax(0, 1fr)); }

        .ts-grid-item {
            width: 100%;
        }

        /* Tablet: max 3 kolom */
        @media (max-width: 991px) {
            .ts-grid-3, .ts-grid-4, .ts-grid-5, .ts-grid-6 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        /* Small tablet: max 2 kolom */
        @media (max-width: 767px) {
            .ts-grid-2, .ts-grid-3, .ts-grid-4, .ts-grid-5, .ts-grid-6 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        /* Mobile: 1 kolom */
        @media (max-width: 479px) {
            .ts-grid-1, .ts-grid-2, .ts-grid-3, .ts-grid-4, .ts-grid-5, .ts-grid-6 {
                grid-template-columns: minmax(0, 320px);
                justify-content: center;
            }
        }

        /* Empty state */
        .top-selling-empty {
            padding: 40px 20px;
            color: #999;
        }

        .top-selling-empty p {
            font-size: 16px;
            margin: 0;
        }

        /* ===== Slider mode (>6 items) ===== */
        .top-selling-slider {
            margin: 0 -10px;
        }

        .top-selling-slider .ts-slide {
            padding: 0 10px;
        }

        /* slick height fix */
        .top-selling-slider .slick-slide {
            height: auto;
        }

        .top-selling-slider .slick-slide>div {
            height: 100%;
        }

        /* center jika jumlah item <= slidesToShow */
        .top-selling-slider.is-few .slick-track {
            display: flex !important;
            justify-content: center;
            align-items: stretch;
        }

        /* arrows optional */
        .top-selling-slider .slick-prev,
        .top-selling-slider .slick-next {
            width: 40px;
            height: 40px;
            border-radius: 999px;
            background: rgba(0, 0, 0, .55);
            z-index: 5;
        }

        .top-selling-slider .slick-prev:hover,
        .top-selling-slider .slick-next:hover {
            background: rgba(0, 0, 0, .75);
        }

        .top-selling-slider .slick-prev:before,
        .top-selling-slider .slick-next:before {
            font-size: 18px;
            opacity: 1;
        }
    </style>
@endpush

@push('scripts')
    @once
        <script>
            (function (factory) {
                // Guard: jQuery belum ada => jangan crash
                if (!window.jQuery) {
                    console.warn('[top-selling-slider] jQuery belum ter-load. Pastikan jquery.min.js sebelum @stack("scripts").');
                    return;
                }
                factory(window.jQuery);
            })(function ($) {

                function clamp(n, min, max) { return Math.max(min, Math.min(max, n)); }

                function initTopSellingSlider(context) {
                    // Guard: Slick belum ada
                    if (!$.fn || !$.fn.slick) {
                        console.warn('[top-selling-slider] Slick belum ter-load. Pastikan slick.min.js sebelum init.');
                        return;
                    }

                    var $root = context ? $(context) : $(document);
                    var $sliders = $root.find('.top-selling-slider');

                    $sliders.each(function () {
                        var $el = $(this);
                        var count = parseInt($el.data('count'), 10) || 0;
                        if (!count) return;

                        // Unslick dulu jika sudah init
                        if ($el.hasClass('slick-initialized')) {
                            try { $el.slick('unslick'); } catch (e) { }
                        }

                        var showDesktop = clamp(parseInt($el.data('show'), 10) || 6, 1, 6);
                        var infinite = count > showDesktop;

                        $el.toggleClass('is-few', count <= showDesktop);

                        $el.slick({
                            rows: 1,
                            slidesToShow: showDesktop,
                            slidesToScroll: 1,
                            infinite: infinite,
                            arrows: infinite,
                            dots: false,
                            autoplay: false,
                            adaptiveHeight: false,
                            swipeToSlide: true,
                            speed: 350,

                            responsive: [
                                { breakpoint: 1200, settings: { slidesToShow: clamp(Math.min(4, count), 1, 4), arrows: count > 4, infinite: count > 4 } },
                                { breakpoint: 992, settings: { slidesToShow: clamp(Math.min(3, count), 1, 3), arrows: count > 3, infinite: count > 3 } },
                                { breakpoint: 768, settings: { slidesToShow: clamp(Math.min(2, count), 1, 2), arrows: count > 2, infinite: count > 2 } },
                                { breakpoint: 480, settings: { slidesToShow: 1, arrows: count > 1, infinite: count > 1 } }
                            ]
                        });
                    });
                }

                function boot() {
                    initTopSellingSlider(document);

                    // Livewire v2 hook (hindari dobel hook)
                    if (window.Livewire && typeof window.Livewire.hook === 'function' && !window.__tsSliderHooked) {
                        window.__tsSliderHooked = true;
                        Livewire.hook('message.processed', function () {
                            initTopSellingSlider(document);
                        });
                    }
                }

                // jQuery ready (aman untuk Bootstrap 3)
                $(boot);

                // Livewire v3 navigated
                document.addEventListener('livewire:navigated', function () {
                    initTopSellingSlider(document);
                });

            });
        </script>
    @endonce
@endpush
