@php
    $currentRoute = Route::currentRouteName();
    $menu = [
        ['label' => 'Beranda', 'link' => route('home'), 'route' => 'home'],
        ['label' => 'Tentang Kami', 'link' => route('about'), 'route' => 'about'],
        ['label' => 'Artikel', 'link' => route('article.index'), 'route' => 'article.index'],
    ];
@endphp
<div class="header-container header-sticky">
    <!--=======  header top  =======-->
    <div class="header-top pt-15 pb-15">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 text-center text-md-start mb-sm-15">
                    <span class="header-top-text">Selamat datang di Toko Online
                        {{ config('app.name', 'Toko ku') }}!</span>
                </div>
                <div class="col-12 col-md-6">
                    <!--=======  header top dropdowns  =======-->
                    <div class="header-top-dropdown d-flex justify-content-center justify-content-md-end">
                        <!--=======  single dropdown  =======-->
                        <div class="single-dropdown">
                            <a href="#" id="changeAccount">
                                <span id="accountMenuName">Akun <i class="fa fa-angle-down"></i></span>
                            </a>
                            <div class="language-currency-list hidden" id="accountList">
                                @if ($isAuthenticated)
                                    <ul>
                                        <li><a href="{{ route('auth.index') }}">Profil</a></li>
                                        <li><a href="{{ route('auth.index') }}#orders">Pesanan</a></li>
                                        <li>
                                            <form method="POST" action="{{ route('auth.logout') }}" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="" style="color:inherit;text-decoration:none;">
                                                    Keluar
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                @else
                                    <ul>
                                        <li><a href="{{ route('login-register.index') }}">Daftar/Masuk</a></li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                        <!--=======  End of single dropdown  =======-->
                    </div>
                    <!--=======  End of header top dropdowns  =======-->
                </div>
            </div>
        </div>
    </div>
    <!--=======  End of header top  =======-->

    <!--=======  Menu Top  =======-->
    <div class="menu-top pt-35 pb-35 pt-sm-20 pb-sm-20">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-lg-3 col-md-3 text-center text-md-start mb-sm-20">
                    <!--=======  logo  =======-->
                    <div class="logo">
                        <a href="{{ route('home') }}">
                            <img width="180" height="54" src="{{ asset('images/logo.png') }}" class="img-fluid"
                                alt="{{ config('app.name') }}">
                        </a>
                    </div>
                    <!--=======  End of logo  =======-->
                </div>

                <div class="col-12 col-lg-6 col-md-5 mb-sm-20">
                    <!--=======  Search bar  =======-->
                    <form wire:submit.prevent="search">
                        <div class="search-bar">
                            <input type="search" wire:model.defer="searchQuery"
                                placeholder="Cari busana impian anda di toko ini ...">
                            <button type="submit">
                                <i class="lnr lnr-magnifier"></i>
                            </button>
                        </div>
                    </form>
                    <!--=======  End of Search bar  =======-->
                </div>

                <div class="col-12 col-lg-3 col-md-4">
                    <!--=======  menu top icons  =======-->
                    <div class="menu-top-icons d-flex justify-content-center align-items-center justify-content-md-end">
                        <!--=======  Wishlist icon  =======-->
                        <div class="single-icon mr-20">
                            <a href="{{ route('wishlist.index') }}">
                                <i class="lnr lnr-heart"></i>
                                <span class="text">Wishlist</span>
                                <span class="count">{{ $wishlistItemsCount }}</span>
                            </a>
                        </div>
                        <!--=======  End of Wishlist icon  =======-->

                        <!--=======  Cart icon  =======-->
                        <div class="single-icon">
                            <a href="javascript:void(0)" id="cart-icon">
                                <i class="lnr lnr-cart"></i>
                                <span class="text">Keranjang</span>
                                <span class="count">{{ $cartItemsCount }}</span>
                            </a>

                            <!-- cart floating box -->
                            <div class="cart-floating-box hidden" id="cart-floating-box">
                                <div class="cart-items">
                                    @if($cartItems->count() > 0)
                                        @foreach ($cartItems as $item)
                                            @php
                                                $product = $item->product;
                                                $variant = $item->variant ?? null;
                                                $sku = $product->sku ?? null;
                                                $link = $sku ? route('catalog.show', $sku) : '#';
                                                $name = $product->name ?? 'Produk';
                                                $vlabel = $variant?->color ?? null;
                                                if ($vlabel && $variant?->size) {
                                                    $vlabel .= ' / ' . $variant->size;
                                                }
                                                $price = $variant?->price ?? $product->price ?? 0;
                                                $qty = (int) ($item->quantity ?? 1);
                                                $primaryImage = $product->images->where('is_primary', true)->first();
                                                $image = $primaryImage?->image_path ? asset('storage/' . $primaryImage?->image_path) : asset('images/placeholder.jpg');
                                            @endphp

                                            <div class="cart-float-single-item d-flex" data-item-id="{{ $item->id }}">
                                                <span class="remove-item">
                                                    <a href="#" wire:click.prevent="removeCartItem({{ $item->id }})"
                                                        class="mini-remove">
                                                        <i class="fa fa-times"></i>
                                                    </a>
                                                </span>

                                                <div class="cart-float-single-item-image">
                                                    <a href="{{ $link }}">
                                                        <img width="300" height="360" src="{{ $image }}"
                                                            class="img-fluid" alt="{{ $name }}">
                                                    </a>
                                                </div>

                                                <div class="cart-float-single-item-desc">
                                                    <p class="product-title">
                                                        <a href="{{ $link }}">
                                                            {{ $name }}
                                                            @if($vlabel)
                                                                <br><small>Varian: {{ $vlabel }}</small>
                                                            @endif
                                                        </a>
                                                    </p>
                                                    <p class="price">
                                                        <span class="quantity">{{ $qty }} x</span>
                                                        Rp{{ number_format($price, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center py-3">Keranjang belanja Anda kosong.</div>
                                    @endif
                                </div>

                                <div class="cart-calculation">
                                    <div class="calculation-details">
                                        <p class="total">
                                            Subtotal
                                            <span id="mini-cart-subtotal">
                                                Rp{{ number_format($cartSubtotal, 0, ',', '.') }}
                                            </span>
                                        </p>
                                    </div>

                                    <div class="floating-cart-btn text-center">
                                        <a class="floating-cart-btn" href="{{ route('checkout.index') }}">Pembayaran</a>
                                        <a class="floating-cart-btn" href="{{ route('cart.index') }}">Lihat Keranjang</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--=======  End of Menu Top  =======-->

    <!--=======  navigation menu  =======-->
    <div class="navigation-menu">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-3">
                    <!--=======  category menu  =======-->
                    <div class="hero-side-category">
                        <!-- Category Toggle Wrap -->
                        <div class="category-toggle-wrap">
                            <!-- Category Toggle -->
                            <button class="category-toggle">
                                <span class="lnr lnr-text-align-left"></span> Pilih kategori
                                <span class="lnr lnr-chevron-down"></span>
                            </button>
                        </div>

                        <!-- Category Menu -->
                        <nav class="category-menu">
                            <ul>
                                @foreach($categories as $category)
                                    @if (!empty($category))
                                        <li>
                                            <a href="{{ route('catalog.index', ['category' => $category]) }}">
                                                {{ $category }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </nav>
                    </div>
                    <!--=======  End of category menu =======-->
                </div>

                <div class="col-12 col-lg-9">
                    <!-- navigation section -->
                    <div class="main-menu">
                        <nav>
                            <ul>
                                @foreach($menu as $item)
                                    <li>
                                        <a href="{{ $item['link'] }}"
                                            class="{{ $currentRoute == $item['route'] ? 'active' : '' }}">
                                            {{ $item['label'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </nav>
                    </div>
                    <!-- end of navigation section -->
                </div>

                <div class="col-12 d-block d-lg-none">
                    <!-- Mobile Menu -->
                    <div class="mobile-menu"></div>
                </div>
            </div>
        </div>
    </div>
    <!--=======  End of navigation menu  =======-->
</div>
