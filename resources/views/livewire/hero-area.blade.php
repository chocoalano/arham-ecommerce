{{-- Hero slider dinamis dari BannerSlider --}}
<div class="hero-area pt-15 mb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="slider-container">
                    {{-- Tambahkan wire:ignore jika pakai plugin slider JS eksternal (Swiper/Slick) --}}
                    <div class="hero-slider-one" @if(!app()->runningUnitTests()) wire:ignore @endif>
                        @forelse($slides as $slide)
                            <div class="hero-slider-item" wire:key="hero-slide-{{ $slide['id'] }}"
                                style="background-image: url('{{ asset($slide['image_108_53']) ?? asset($slide['image']) }}'); background-size: cover; background-position: center;">
                                <div
                                    class="slider-content d-flex flex-column justify-content-center align-items-start h-100">
                                    @if(!empty($slide['desc']))
                                        <p class="mb-2">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($slide['desc']), 120) }}
                                        </p>
                                    @endif

                                    <h1 class="mb-3">
                                        {{ $slide['name'] }}
                                        @if(!empty($slide['discount_percent']))
                                            <span class="badge bg-danger ms-2">-{{ $slide['discount_percent'] }}%</span>
                                        @endif
                                    </h1>

                                    <a href="{{ $slide['link_url'] }}" class="pataku-btn slider-btn-1">
                                        {{ $slide['button_text'] }}
                                    </a>
                                </div>
                            </div>
                        @empty
                            {{-- Fallback ketika belum ada banner aktif --}}
                            <div class="hero-slider-item" style="background:#f5f5f5;">
                                <div
                                    class="slider-content d-flex flex-column justify-content-center align-items-start h-100">
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
                <!--=======  feature area  =======-->

                <div class="feature-area">

                    <!--=======  End of single feature  =======-->
                    <!--=======  single feature  =======-->

                    <div class="single-feature mb-md-20 mb-sm-20">
                        <span class="icon"><i class="lnr lnr-phone"></i></span>
                        <p>Bantuan 24/7 <span>Hubungi kami 24 jam sehari</span></p>
                    </div>

                    <!--=======  End of single feature  =======-->
                    <!--=======  single feature  =======-->

                    <div class="single-feature mb-xxs-20">
                        <span class="icon"><i class="lnr lnr-undo"></i></span>
                        <p>100% Uang Kembali <span>Jaminan untuk kepuasan</span></p>
                    </div>

                    <!--=======  End of single feature  =======-->
                    <!--=======  single feature  =======-->

                    <div class="single-feature mb-xxs-20">
                        <span class="icon"><i class="lnr lnr-gift"></i></span>
                        <p>Pembayaran Aman <span>Pembayaran yang aman</span></p>
                    </div>

                    <!--=======  End of single feature  =======-->
                </div>

                <!--=======  End of feature area  =======-->
            </div>
        </div>
    </div>
</div>
