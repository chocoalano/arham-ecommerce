@php
    $cats = collect($categories ?? [])->take(4)->values();
    $count = $cats->count();
    $fallback = asset('images/placeholder.jpg');

    $desktopCols = min(4, max(1, $count));

    $image = function ($cat) use ($fallback) {
        if (!is_array($cat)) {
            return $fallback;
        }

        foreach (['image_99_119', 'image_51_52', 'image_27_28', 'image_108_53', 'image'] as $key) {
            $value = $cat[$key] ?? null;
            if (is_string($value) && trim($value) !== '') {
                return $value;
            }
        }

        return $fallback;
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

        <div class="row">
            <div class="col-xs-12">
                @if($count > 0)
                    <div class="fc-dynamic-grid fc-cols-{{ $desktopCols }}">
                        @foreach($cats as $cat)
                            <div class="fc-dynamic-item" wire:key="featured-category-{{ $cat['id'] }}">
                                <a href="{{ $cat['url'] ?? '#' }}" class="fc-dynamic-card">
                                    <div class="fc-dynamic-media">
                                        <img src="{{ $image($cat) }}" alt="{{ $cat['name'] ?? 'Kategori' }}"
                                            class="img-responsive fc-dynamic-img" loading="lazy"
                                            onerror="this.onerror=null;this.src='{{ $fallback }}';">
                                    </div>
                                    <div class="fc-dynamic-overlay"></div>
                                    <div class="fc-dynamic-title">
                                        <div class="fc-dynamic-name">{{ $cat['name'] ?? 'Kategori' }}</div>
                                        <div class="fc-dynamic-meta">{{ (int) ($cat['count'] ?? 0) }} produk aktif</div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="module-admin-alert" role="alert">
                        <strong>Info untuk Admin:</strong>
                        Belum ada kategori pilihan yang tampil. Pastikan data kategori tersedia, aktif, dan ditandai sebagai highlight.
                    </div>

                    <div class="fc-dynamic-grid fc-cols-4 fc-placeholder-grid">
                        @for($i = 1; $i <= 4; $i++)
                            <div class="fc-dynamic-item" wire:key="featured-category-placeholder-{{ $i }}">
                                <div class="fc-dynamic-card fc-dynamic-card--placeholder" aria-hidden="true">
                                    <div class="fc-dynamic-media">
                                        <img src="{{ $fallback }}" alt="Placeholder kategori" class="img-responsive fc-dynamic-img"
                                            loading="lazy">
                                    </div>
                                    <div class="fc-dynamic-overlay"></div>
                                    <div class="fc-dynamic-title">
                                        <div class="fc-dynamic-name">Kategori</div>
                                        <div class="fc-dynamic-meta">0 produk aktif</div>
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

        .fc-dynamic-grid {
            display: grid;
            gap: 20px;
        }

        .fc-dynamic-grid.fc-cols-1 {
            grid-template-columns: minmax(0, 1fr);
        }

        .fc-dynamic-grid.fc-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .fc-dynamic-grid.fc-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .fc-dynamic-grid.fc-cols-4 {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .fc-dynamic-item {
            width: 100%;
        }

        .fc-dynamic-card {
            position: relative;
            display: block;
            overflow: hidden;
            border-radius: 14px;
            text-decoration: none;
            background: #f2f2f2;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
            min-height: 260px;
        }

        .fc-dynamic-media {
            width: 100%;
            height: 100%;
            min-height: 260px;
        }

        .fc-dynamic-img {
            width: 100%;
            height: 100%;
            min-height: 260px;
            object-fit: cover;
            display: block;
        }

        .fc-dynamic-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.08));
        }

        .fc-dynamic-title {
            position: absolute;
            left: 14px;
            right: 14px;
            bottom: 12px;
            z-index: 2;
            color: #fff;
        }

        .fc-dynamic-name {
            font-size: 18px;
            font-weight: 700;
            line-height: 1.2;
        }

        .fc-dynamic-meta {
            font-size: 12px;
            opacity: 0.9;
            margin-top: 4px;
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

        .fc-placeholder-grid {
            margin-top: 16px;
        }

        .fc-dynamic-card--placeholder {
            opacity: 0.74;
        }

        .fc-dynamic-card--placeholder .fc-dynamic-img {
            filter: grayscale(0.35);
        }

        @media (max-width: 991px) {
            .fc-dynamic-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }
        }

        @media (max-width: 575px) {
            .fc-dynamic-grid {
                grid-template-columns: minmax(0, 1fr) !important;
            }
        }
    </style>
@endpush
