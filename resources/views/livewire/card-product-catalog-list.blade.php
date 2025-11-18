{{-- Card product list view dengan PTK JS integration --}}
<div class="ptk-product shop-list-view-product" data-product-id="{{ (int) $productId }}">
    <div class="image">
        <a href="{{ $p['url'] ?? '#' }}" title="Detail" onclick="return PTK.detail(@json($p['url'] ?? null));">
            <img width="255" height="260"
                 src="{{ $p['image_51_52'] ?? $p['image'] ?? asset('images/placeholder.jpg') }}"
                 class="img-fluid"
                 alt="{{ $p['name'] ?? 'Product' }}"
                 loading="lazy">
        </a>

        {{-- Badge produk --}}
        <div class="product-badge">
            @if($p['discount'] ?? null)
                <span class="discount-badge">-{{ $p['discount'] }}%</span>
            @endif
            @if($p['is_new'] ?? false)
                <span class="new-badge">Baru</span>
            @endif
        </div>
    </div>

    <div class="content">
        <p class="product-title">
            <a href="{{ $p['url'] ?? '#' }}" onclick="return PTK.detail(@json($p['url'] ?? null));">
                {{ $p['name'] ?? 'Product Name' }}
            </a>
        </p>

        {{-- Rating --}}
        <div class="rating">
            @php
                $rating = $p['rating_avg'] ?? 0;
            @endphp
            @for($i = 1; $i <= 5; $i++)
                <i class="lnr lnr-star {{ $i <= floor($rating) ? 'active' : '' }}"></i>
            @endfor
            @if($p['rating_count'] ?? 0)
                <span class="rating-count">({{ $p['rating_count'] }})</span>
            @endif
        </div>

        {{-- Harga --}}
        <p class="product-price">
            @if(!empty($p['from_variant']) && $p['from_variant'] > 0)
                <span class="discounted-price">Rp {{ number_format($p['from_variant'], 0, ',', '.') }}</span>
            @else
                @if(($p['sale_price'] ?? null) && $p['sale_price'] < ($p['price'] ?? 0))
                    <span class="main-price discounted">Rp{{ number_format($p['price'], 0, ',', '.') }}</span>
                    <span class="discounted-price">Rp{{ number_format($p['sale_price'], 0, ',', '.') }}</span>
                @else
                    <span class="discounted-price">Rp{{ number_format($p['price'] ?? 0, 0, ',', '.') }}</span>
                @endif
            @endif
        </p>

        {{-- Deskripsi --}}
        @if(!empty($p['short_description'] ?? null))
            <p class="product-description">
                {{ \Illuminate\Support\Str::limit(strip_tags($p['short_description']), 180) }}
            </p>
        @endif

        {{-- Action buttons --}}
        <div class="hover-icons">
            {{-- Quick view --}}
            <a class="hover-icon" href="{{ $p['url'] ?? '#' }}" title="Lihat cepat" onclick="return PTK.quickView({{ (int) $productId }});">
                <i class="lnr lnr-eye"></i>
            </a>

            {{-- Wishlist toggle --}}
            <a class="hover-icon" href="#"
               title="{{ $inWishlist ? 'Hapus dari wishlist' : 'Tambah ke wishlist' }}"
               aria-pressed="{{ $inWishlist ? 'true' : 'false' }}"
               onclick="return PTK.wishlistToggle({{ (int) $productId }}, this);">
                <i class="lnr lnr-heart {{ $inWishlist ? 'active' : '' }}"></i>
            </a>

            {{-- Add to cart --}}
            <a class="hover-icon" href="#" title="Tambah ke keranjang" onclick="return PTK.addToCart({{ (int) $productId }}, 1, null, this);">
                <i class="lnr lnr-cart"></i>
            </a>
        </div>
    </div>
</div>
