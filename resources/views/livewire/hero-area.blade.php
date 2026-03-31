<style>
    .slider-container {
        width: 100%;
        height: auto;
        overflow: hidden;
        border-radius: 12px;
    }

    .hero-slider-one {
        width: 100%;
        height: auto;
    }

    .hero-slider-item {
        position: relative;
        width: 100%;
        overflow: hidden;
        background: #f8f8f8;
        border-radius: 12px;
        aspect-ratio: 21 / 8;
    }

    .hero-slider-media {
        display: block;
        width: 100%;
        height: 100%;
    }

    .hero-slider-media picture {
        display: block;
        width: 100%;
        height: 100%;
    }

    .hero-slider-image {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
        object-position: center;
    }

    .hero-slider-item .slider-content {
        position: absolute;
        inset: 0;
        z-index: 2;
        height: 100%;
    }

    /* Jika pakai Slick */
    .hero-slider-one .slick-list,
    .hero-slider-one .slick-track {
        height: auto !important;
    }

    .hero-slider-one .slick-slide {
        height: auto !important;
    }

    .hero-slider-one .slick-slide>div {
        height: auto;
    }

    /* Jika pakai Swiper */
    .hero-slider-one .swiper-wrapper,
    .hero-slider-one .swiper-slide {
        height: auto;
    }

    /* Tablet */
    @media (max-width: 991.98px) {
        .slider-container {
            border-radius: 10px;
        }

        .hero-slider-item {
            aspect-ratio: 16 / 7;
            border-radius: 10px;
        }
    }

    /* Mobile */
    @media (max-width: 767.98px) {
        .slider-container {
            border-radius: 8px;
        }

        .hero-slider-item {
            aspect-ratio: 16 / 9;
            border-radius: 8px;
        }

        .hero-slider-image {
            object-fit: contain;
            background: #fff;
        }
    }

    .feature-area {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        justify-content: space-between;
    }

    .feature-area .single-feature {
        flex: 1 1 calc(33.333% - 16px);
        min-width: 220px;
    }

    @media (max-width: 767.98px) {
        .feature-area .single-feature {
            flex: 1 1 100%;
            min-width: 100%;
        }
    }
</style>

{{-- Hero slider dinamis dari BannerSlider --}}
<div class="hero-area pt-15 mb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="slider-container">
                    <div class="hero-slider-one" @if(!app()->runningUnitTests()) wire:ignore @endif>
                        @forelse($slides as $slide)
                            @php
                                $desktopImage = !empty($slide['image_108_53'])
                                    ? asset($slide['image_108_53'])
                                    : asset($slide['image']);

                                $mobileImage = !empty($slide['image_mobile'])
                                    ? asset($slide['image_mobile'])
                                    : $desktopImage;
                            @endphp

                            <div class="hero-slider-item" wire:key="hero-slide-{{ $slide['id'] }}">
                                <picture class="hero-slider-media">
                                    <source media="(max-width: 767.98px)" srcset="{{ $mobileImage }}">
                                    <img src="{{ $desktopImage }}" alt="{{ $slide['title'] ?? 'Banner slider' }}"
                                        class="hero-slider-image">
                                </picture>

                                <div
                                    class="slider-content d-flex flex-column justify-content-center align-items-start h-100">
                                </div>
                            </div>
                        @empty
                            <div class="hero-slider-item">
                                <div
                                    class="slider-content d-flex flex-column justify-content-center align-items-start h-100 p-4">
                                    <p>Discover our latest collection</p>
                                    <h1>NEW <span>ARRIVALS</span></h1>
                                    <a href="{{ url('/shop') }}" class="pataku-btn slider-btn-1">Belanja sekarang</a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 pt-40 pb-40">
                <div class="feature-area">
                    <div class="single-feature mb-md-20 mb-sm-20">
                        <span class="icon"><i class="lnr lnr-phone"></i></span>
                        <p>Bantuan 24/7 <span>Hubungi kami 24 jam sehari</span></p>
                    </div>

                    <div class="single-feature mb-xxs-20">
                        <span class="icon"><i class="lnr lnr-undo"></i></span>
                        <p>100% Uang Kembali <span>Jaminan untuk kepuasan</span></p>
                    </div>

                    <div class="single-feature mb-xxs-20">
                        <span class="icon"><i class="lnr lnr-gift"></i></span>
                        <p>Pembayaran Aman <span>Pembayaran yang aman</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>