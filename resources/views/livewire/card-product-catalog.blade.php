{{-- Single product card component --}}
<div class="ptk-product" data-product-id="{{ (int) $productId }}">
    <div class="image">
        <a href="{{ $p['url'] ?? '#' }}" onclick="return PTK.detail(@json($p['url'] ?? null));">
            <img width="300" height="360" src="{{ $p['image_99_119'] ?? $p['image'] }}" class="img-fluid" alt="{{ $p['name'] }}" loading="lazy">
        </a>

        <!--=======  hover icons  =======-->

        <a class="hover-icon" href="#" data-bs-toggle="modal" data-bs-target="#quick-view-modal-container" onclick="return PTK.quickView({{ (int) $productId }});">
            <i class="lnr lnr-eye"></i>
        </a>

        <a class="hover-icon" href="#" onclick="return PTK.wishlistToggle({{ (int) $productId }}, this);">
            <i class="lnr lnr-heart {{ $inWishlist ? 'active' : '' }}"></i>
        </a>

        <a class="hover-icon" href="#" onclick="return PTK.addToCart({{ (int) $productId }}, 1, null, this);">
            <i class="lnr lnr-cart"></i>
        </a>

        <!--=======  End of hover icons  =======-->

        <!--=======  badge  =======-->

        <div class="product-badge">
            @isset($p['is_new'])
                @if($p['is_new'])
                    <span class="new-badge">NEW</span>
                @endif
            @endisset
            @if(!empty($p['discount']))
                <span class="discount-badge">-{{ $p['discount'] }}%</span>
            @endif
        </div>

        <!--=======  End of badge  =======-->

    </div>
    <div class="content">
        <p class="product-title">
            <a href="{{ $p['url'] ?? '#' }}" onclick="return PTK.detail(@json($p['url'] ?? null));">
                {{ \Illuminate\Support\Str::limit($p['name'], 70) }}
            </a>
        </p>
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
    <div class="rating">
        @php $stars = (int) round($p['rating_avg'] ?? 0); @endphp
        @for($i = 1; $i <= 5; $i++)
            <i class="lnr lnr-star {{ $i <= $stars ? 'active' : '' }}"></i>
        @endfor
    </div>
</div>
