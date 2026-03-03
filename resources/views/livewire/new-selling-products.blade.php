{{-- resources/views/livewire/new-selling-products.blade.php --}}
@php
    $items = collect($items ?? [])->take(6)->values();
    $count = $items->count();

    if ($count <= 2) {
        $desktopCols = max(1, $count);
    } elseif ($count === 4) {
        $desktopCols = 2;
    } else {
        $desktopCols = 3;
    }
@endphp

<div class="new-selling-products m-b-80">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center m-b-40">
                <div class="section-title">
                    <h2><span>Rekomendasi</span> Produk Terbaru</h2>
                    <p>Pilihan produk terbaru yang direkomendasikan untuk Anda.</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                @if($count > 0)
                    <div class="new-selling-grid ns-cols-{{ $desktopCols }}">
                        @foreach($items as $p)
                            <div class="ns-grid-item" wire:key="new-product-{{ $p['id'] }}">
                                <livewire:card-product-catalog :productId="$p['id']" :key="'new-product-' . $p['id']" />
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="module-admin-alert" role="alert">
                        <strong>Info untuk Admin:</strong>
                        Belum ada data rekomendasi produk terbaru. Pastikan produk aktif sudah tersedia agar section ini terisi.
                    </div>

                    <div class="new-selling-grid ns-cols-3 new-selling-placeholder-grid">
                        @for($i = 1; $i <= 6; $i++)
                            <div class="ns-grid-item" wire:key="new-placeholder-{{ $i }}">
                                <div class="ptk-product ptk-product-placeholder" aria-hidden="true">
                                    <div class="image">
                                        <img width="300" height="360" src="{{ asset('images/placeholder.jpg') }}" class="img-fluid" alt="Placeholder produk terbaru" loading="lazy">
                                    </div>
                                    <div class="content">
                                        <p class="product-title">Produk Terbaru</p>
                                        <p class="product-price"><span class="main-price">-</span></p>
                                    </div>
                                    <div class="rating">
                                        @for($star = 1; $star <= 5; $star++)
                                            <i class="lnr lnr-star"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .m-b-40 {
            margin-bottom: 40px;
        }

        .m-b-80 {
            margin-bottom: 80px;
        }

        .new-selling-grid {
            display: grid;
            gap: 20px;
            justify-content: center;
            align-items: stretch;
        }

        .new-selling-grid.ns-cols-1 {
            grid-template-columns: minmax(0, 320px);
        }

        .new-selling-grid.ns-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 320px));
        }

        .new-selling-grid.ns-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .ns-grid-item {
            width: 100%;
        }

        .new-selling-placeholder-grid {
            margin-top: 16px;
        }

        .module-admin-alert {
            border: 1px solid #f5c06a;
            background: #fff8e8;
            color: #7a4d00;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 14px;
            line-height: 1.45;
        }

        .ptk-product-placeholder {
            opacity: 0.72;
        }

        .ptk-product-placeholder .image img {
            filter: grayscale(0.35);
        }

        .ptk-product-placeholder .content {
            text-align: center;
        }

        .ptk-product-placeholder .product-title {
            color: #777;
        }

        .ptk-product-placeholder .product-price .main-price {
            color: #999;
        }

        .ptk-product-placeholder .rating {
            color: #d2d2d2;
            margin: 0;
        }

        @media (max-width: 991px) {
            .new-selling-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }
        }

        @media (max-width: 575px) {
            .new-selling-grid {
                grid-template-columns: minmax(0, 1fr) !important;
            }
        }
    </style>
@endpush
