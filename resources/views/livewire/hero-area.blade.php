{{-- Hero slider dinamis dari Products & Product Images --}}
<div class="hero-area pt-15 mb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="slider-container">
                    {{-- Tambahkan wire:ignore jika pakai plugin slider JS eksternal (Swiper/Slick) --}}
                    <div class="hero-slider-one" @if(!app()->runningUnitTests()) wire:ignore @endif>
                        @forelse($slides as $slide)
                            <div class="hero-slider-item"
                                 wire:key="hero-slide-{{ $slide['id'] }}"
                                 style="background-image: url('{{ $slide['image'] }}'); background-size: cover; background-position: center;">
                                <div class="slider-content d-flex flex-column justify-content-center align-items-start h-100">
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

                                    <div class="d-flex align-items-center gap-3 mb-4">
                                        @if(!empty($slide['sale_price']) && $slide['sale_price'] < $slide['price'])
                                            <span class="fs-4 fw-bold">
                                                Rp {{ number_format($slide['final_price'], 0, ',', '.') }}
                                            </span>
                                            <span class="text-decoration-line-through text-muted">
                                                Rp {{ number_format($slide['price'], 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="fs-4 fw-bold">
                                                Rp {{ number_format($slide['price'], 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>

                                    <a href="{{ $slide['product_url'] }}" class="pataku-btn slider-btn-1">Belanja sekarang</a>
                                </div>
                            </div>
                        @empty
                            {{-- Fallback ketika belum ada produk featured --}}
                            <div class="hero-slider-item" style="background:#f5f5f5;">
                                <div class="slider-content d-flex flex-column justify-content-center align-items-start h-100">
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
    </div>
</div>
