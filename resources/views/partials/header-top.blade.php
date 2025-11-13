<div class="header-top pt-15 pb-15">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6 text-center text-md-start mb-sm-15">
                <span class="header-top-text">Selamat Datang di {{ config('app.name') }} !</span>
            </div>
            <div class="col-12 col-md-6">
                <div class="header-top-dropdown d-flex justify-content-center justify-content-md-end">
                    <div class="single-dropdown">
                        <a href="#" id="changeAccount">
                            <span id="accountMenuName">
                                @auth('customer')
                                    {{ Auth::guard('customer')->user()->name }}
                                @else
                                    Akun Saya
                                @endauth
                                <i class="fa fa-angle-down"></i>
                            </span>
                        </a>
                        <div class="language-currency-list hidden" id="accountList">
                            <ul>
                                @auth('customer')
                                    <li><a href="{{ route('auth.index') }}">Profil</a></li>
                                    <li><a href="{{ route('checkout.index') }}">Pembayaran</a></li>
                                    <li>
                                        <form action="{{ route('auth.logout') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0; color: inherit;">
                                                Keluar
                                            </button>
                                        </form>
                                    </li>
                                @else
                                    <li><a href="{{ route('login-register.index') }}">Masuk</a></li>
                                    <li><a href="{{ route('login-register.index') }}">Daftar</a></li>
                                @endauth
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
