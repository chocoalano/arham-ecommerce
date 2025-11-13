{{-- Card tanpa Livewire events — full onclick JS --}}
<div class="ptk-product shop-grid-view-product" data-product-id="{{ (int) $productId }}">
    <div class="image">
        <a href="{{ $p['url'] ?? '#' }}" title="Detail" onclick="return PTK.detail(@json($p['url'] ?? null));">
            <img width="198" height="238" src="{{ $p['image_99_119'] ?? $p['image'] }}" class="img-fluid" alt="{{ $p['name'] }}" loading="lazy">
        </a>

        {{-- Quick view --}}
        <a class="hover-icon" href="{{ $p['url'] ?? '#' }}" title="Lihat cepat" onclick="return PTK.quickView({{ (int) $productId }});">
            <i class="lnr lnr-eye"></i>
        </a>

        {{-- Wishlist toggle --}}
        <a class="hover-icon" href="#" title="{{ $inWishlist ? 'Hapus dari wishlist' : 'Tambah ke wishlist' }}"
           aria-pressed="{{ $inWishlist ? 'true' : 'false' }}"
           onclick="return PTK.wishlistToggle({{ (int) $productId }}, this);">
            <i class="lnr lnr-heart {{ $inWishlist ? 'active' : '' }}"></i>
        </a>

        {{-- Add to cart --}}
        <a class="hover-icon" href="#" title="Tambah ke keranjang" onclick="return PTK.addToCart({{ (int) $productId }}, 1, null, this);">
            <i class="lnr lnr-cart"></i>
        </a>

        {{-- Badge produk --}}
        <div class="product-badge">
            @if(!empty($p['discount']))
                <span class="discount-badge">-{{ $p['discount'] }}%</span>
            @endif
            @isset($p['is_new'])
                @if($p['is_new'])
                    <span class="new-badge">Baru</span>
                @endif
            @endisset
        </div>
    </div>

    <div class="content">
        <p class="product-title">
            <a href="{{ $p['url'] ?? '#' }}" onclick="return PTK.detail(@json($p['url'] ?? null));">
                {{ \Illuminate\Support\Str::limit($p['name'], 70) }}
            </a>
        </p>

        {{-- Harga --}}
        <p class="product-price">
            @if(!empty($p['from_variant']) && $p['from_variant'] > 0)
                <span class="discounted-price">Rp {{ number_format($p['from_variant'], 0, ',', '.') }}</span>
            @else
                @if(!empty($p['sale_price']) && $p['sale_price'] < $p['price'])
                    <span class="main-price discounted">Rp {{ number_format($p['price'], 0, ',', '.') }}</span>
                    <span class="discounted-price">Rp {{ number_format($p['final_price'], 0, ',', '.') }}</span>
                @else
                    <span class="main-price">Rp {{ number_format($p['price'], 0, ',', '.') }}</span>
                @endif
            @endif
        </p>
    </div>

    {{-- Rating bintang --}}
    <div class="rating">
        @php $stars = (int) round($p['rating_avg'] ?? 0); @endphp
        @for($i = 1; $i <= 5; $i++)
            <i class="lnr lnr-star {{ $i <= $stars ? 'active' : '' }}"></i>
        @endfor
    </div>
</div>
