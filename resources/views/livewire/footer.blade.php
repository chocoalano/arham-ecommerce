<!--=============================================
    =            Footer (Livewire Component)      =
    =============================================-->

<div class="footer-container pt-60 pb-60">
    <!--=======  footer navigation container  =======-->
    <div class="footer-navigation-container mb-60">
        <div class="container">
            <div class="row">
                <!-- Contact Info Block -->
                <div class="col-12 col-lg-4 col-md-6 col-sm-6 mb-20 mb-lg-0 mb-xl-0 mb-md-35 mb-sm-35">
                    <div class="single-footer">
                        @foreach($footerBlocks as $block)
                            <div class="single-block mb-35">
                                <h3 class="footer-title">{{ $block['title'] }}</h3>
                                <p>{!! $block['content'] !!}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Products Links -->
                <div class="col-12 col-lg-2 col-md-6 col-sm-6 mb-20 mb-lg-0 mb-xl-0 mb-md-35 mb-sm-35">
                    <div class="single-footer">
                        <h3 class="footer-title mb-20">Produk</h3>
                        <ul>
                            @foreach($productsLinks as $link)
                                <li><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Company Links (from Pages model) -->
                <div class="col-12 col-lg-2 col-md-6 col-sm-6 mb-20 mb-lg-0 mb-xl-0 mb-md-35 mb-sm-35">
                    <div class="single-footer">
                        <h3 class="footer-title mb-20">Perusahaan Kami</h3>
                        <ul>
                            @forelse($companyLinks as $link)
                                <li><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
                            @empty
                                <li><a href="{{ route('page.show', ['slug' => 'about']) }}">Tentang Kami</a></li>
                                <li><a href="{{ route('catalog.index') }}">Produk</a></li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Newsletter & Address -->
                <div class="col-12 col-lg-4 col-md-6 col-sm-6">
                    <div class="single-footer mb-35">
                        <h3 class="footer-title mb-20">Berlangganan</h3>
                        <div class="newsletter-form mb-20">
                            <form wire:submit.prevent="subscribe" class="subscribe-form">
                                <input type="email" wire:model="email" placeholder="Alamat email kamu" required>
                                <button type="submit" value="submit">
                                    <i class="lnr lnr-envelope"></i>
                                </button>
                            </form>
                        </div>

                        @if (session()->has('newsletter_success'))
                            <div class="alert alert-success mb-20"
                                style="padding: 10px; border-radius: 4px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb;">
                                {{ session('newsletter_success') }}
                            </div>
                        @endif

                        @if (session()->has('newsletter_error'))
                            <div class="alert alert-danger mb-20"
                                style="padding: 10px; border-radius: 4px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
                                {{ session('newsletter_error') }}
                            </div>
                        @endif

                        @error('email')
                            <div class="alert alert-danger mb-20"
                                style="padding: 10px; border-radius: 4px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="single-footer">
                        <h3 class="footer-title mb-20">Alamat</h3>
                        @php
                            $alamat = \App\Models\Page::where([
                                'slug' => 'contact',
                                'is_active' => true
                            ])->first();
                        @endphp
                        <p>{{ $alamat->sections['contact_info']['address'] ?? 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Animi corporis, necessitatibus officiis dolor facere ipsum rem sed itaque ea eos.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--=======  End of footer navigation container  =======-->

    <!--=======  footer social link container  =======-->
    <div class="footer-social-link-container pt-15 pb-15 mb-60">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-lg-6 col-md-7 mb-sm-15 text-start text-sm-center text-lg-start">
                    <div class="app-download-area">
                        <<span class="title">Keuntungan Belanja Sekarang:</span>
                            <strong class="text-white">Gratis Ongkir dan Jaminan Pengembalian</strong>
                            <p class="text-white">Nikmati layanan pengiriman gratis ke seluruh Indonesia* dan kemudahan pengembalian barang
                                dalam 30 hari. **Belanja tanpa khawatir!**</p>
                    </div>
                </div>
                <div class="col-12 col-lg-6 col-md-5 text-start text-sm-center text-md-end">
                    <div class="social-link">
                        <span class="title">Follow Us:</span>
                        <ul>
                            @foreach($socialLinks as $social)
                                <li>
                                    <a target="_blank" href="{{ $social['url'] }}" title="{{ $social['name'] }}"
                                        rel="noopener noreferrer">
                                        <i class="fa {{ $social['icon'] }}"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--=======  End of footer social link container  =======-->

    <!--=======  footer bottom navigation  =======-->
    <div class="footer-bottom-navigation text-center mb-20">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="navigation-container">
                        <ul>
                            @foreach($footerPages as $index => $page)
                                <li>
                                    <a
                                        href="{{ $page->slug === 'about' ? route('about') : route('page.show', $page->slug) }}">
                                        {{ $page->title }}
                                    </a>
                                    @if(!$loop->last)
                                        <span class="separator">{{ $index < 2 ? '|' : '-' }}</span>
                                    @endif
                                </li>
                            @endforeach
                            @if (auth('customer')->check())
                                <li>
                                    <a href="{{ route('auth.index') }}">My Account</a>
                                    <span class="separator">-</span>
                                </li>
                                <li>
                                    <a href="{{ route('auth.index') }}">Order Status</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--=======  End of footer bottom navigation  =======-->

    <!--=======  copyright section  =======-->
    <div class="copyright-section text-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <p class="copyright-text">
                        Copyright &copy; {{ date('Y') }}
                        <a href="{{ route('home') }}">Arham E-Commerce</a>.
                        All Rights Reserved
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!--=======  End of copyright section  =======-->
</div>
<!--=====  End of Footer  ======-->
