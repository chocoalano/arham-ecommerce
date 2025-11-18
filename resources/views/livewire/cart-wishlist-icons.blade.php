<!--=======  Wishlist & Cart Icons (Livewire)  =======-->
<div class="menu-top-icons d-flex justify-content-center align-items-center justify-content-md-end">
    <!--=======  single icon  =======-->
    <div class="single-icon mr-20">
        <a href="{{ route('wishlist.index') }}">
            <i class="lnr lnr-heart"></i>
            <span class="text">Wishlist</span>
            <span class="count">{{ $wishlistCount }}</span>
        </a>
    </div>

    <!--=======  End of single icon  =======-->

    <!--=======  single icon  =======-->
    <div class="single-icon">
        <a href="javascript:void(0)" id="cart-icon">
            <i class="lnr lnr-cart"></i>
            <span class="text">My Cart</span>
            <span class="count">{{ $cartCount }}</span>
        </a>

        <!-- cart floating box -->
        <div class="cart-floating-box hidden" id="cart-floating-box">
            @if($cartItems)
                <div class="cart-items">
                    @foreach($cartItems as $item)
                        <div class="cart-float-single-item d-flex">
                            <span class="remove-item">
                                <a href="#" wire:click.prevent="removeCartItem({{ $item['id'] }})">
                                    <i class="fa fa-times"></i>
                                </a>
                            </span>
                            <div class="cart-float-single-item-image">
                                <a href="{{ $item['url'] }}">
                                    <img width="198" height="238" src="{{ $item['image'] }}" class="img-fluid" alt="{{ $item['name'] }}" loading="lazy">
                                </a>
                            </div>
                            <div class="cart-float-single-item-desc">
                                <p class="product-title">
                                    <a href="{{ $item['url'] }}">{{ $item['name'] }}</a>
                                </p>
                                <p class="price">
                                    <span class="quantity">{{ $item['quantity'] }} x</span>
                                    Rp {{ number_format($item['price'], 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="cart-calculation">
                    <div class="calculation-details">
                        <p class="total">Subtotal <span>Rp {{ number_format($cartSubtotal, 0, ',', '.') }}</span></p>
                    </div>
                    <div class="floating-cart-btn text-center">
                        <a class="floating-cart-btn" href="{{ route('checkout.index') }}">Checkout</a>
                        <a class="floating-cart-btn" href="{{ route('cart.index') }}">View Cart</a>
                    </div>
                </div>
            @else
                <div class="cart-items">
                    <p class="text-center py-4">Your cart is empty</p>
                </div>
            @endif
        </div>
        <!-- end of cart floating box -->
    </div>

    <!--=======  End of single icon  =======-->
</div>
<!--=======  End of menu top icons  =======-->
