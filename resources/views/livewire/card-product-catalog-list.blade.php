<div class="ptk-product shop-list-view-product">
    <div class="image">
        <a href="{{ $productUrl }}">
            <img width="300" height="360" src="{{ $imgSrc }}" class="img-fluid" alt="{{ $imgAlt }}">
        </a>
        <div class="product-badge">
            @if($isNew)<span class="new-badge">NEW</span>@endif
            @if($discountPct)<span class="discount-badge">-{{ $discountPct }}%</span>@endif
        </div>
    </div>
    <div class="content">
        <p class="product-title"><a href="{{ $productUrl }}">{{ $product->name }}</a></p>
        <div class="rating ">
            @for($i = 1; $i <= 5; $i++)
                <i class="lnr lnr-star {{ $i <= floor($avg) ? 'active' : '' }}"></i>
            @endfor
        </div>
        <p class="product-price">
            @if($hasSale)
                <span class="main-price discounted">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                <span class="discounted-price">Rp{{ number_format($product->sale_price, 0, ',', '.') }}</span>
            @else
                <span class="discounted-price">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
            @endif
        </p>
        @if(!empty($product->short_description))
            <p class="product-description">
                {{ \Illuminate\Support\Str::limit(strip_tags($product->short_description), 180) }}
            </p>
        @endif
        <div class="hover-icons">
            <a class="hover-icon" href="#" data-bs-toggle="modal" data-bs-target="#quick-view-modal-container"><i
                    class="lnr lnr-eye"></i></a>
            <a class="hover-icon" href="#"><i class="lnr lnr-heart"></i></a>
            <a class="hover-icon" href="#"><i class="lnr lnr-cart"></i></a>
        </div>
    </div>
</div>
