
<!DOCTYPE html>
<html class="no-js" lang="zxx">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="route-cart-store" content="{{ route('cart.store') }}">
	<meta name="route-wishlist-store" content="{{ route('wishlist.store') }}">
	<meta name="route-login" content="{{ route('login-register.index') }}">
	<title>{{ config('app.name') }}</title>
	<meta name="description" content="">

	<!-- Favicon -->
	<link rel="icon" href="{{ asset('images/favicon.ico') }}">

	<!-- Google Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;1,100;1,300;1,400;1,700&family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">

	<!-- CSS
	============================================ -->
	<!-- Bootstrap CSS -->
	<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

	<!-- FontAwesome CSS -->
	<link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">

	<!-- Linear Icon CSS -->
	<link href="{{ asset('css/linear-icon.css') }}" rel="stylesheet">

	<!-- Plugins CSS -->
	<link href="{{ asset('css/plugins.css') }}" rel="stylesheet">

	<!-- Helper CSS -->
	<link href="{{ asset('css/helper.css') }}" rel="stylesheet">

	<!-- Main CSS -->
	<link href="{{ asset('css/main.css') }}" rel="stylesheet">

	<!-- Toastr CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

	<!-- Modernizer JS -->
	<script src="{{ asset('js/vendor/modernizr-2.8.3.min.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
	@livewireStyles
    @stack('styles')
</head>

<body>

	<!--=============================================
	=            Header One         =
	=============================================-->

	<div class="header-container header-sticky">

		@include('partials.header-top')

		<!--=======  Menu Top  =======-->

		<div class="menu-top pt-35 pb-35 pt-sm-20 pb-sm-20">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-12 col-lg-3 col-md-3 text-center text-md-start mb-sm-20">
						<!--=======  logo  =======-->

						<div class="logo">
							<a href="{{ route('home') }}">
								<img width="120" height="10" src="{{ asset('images/logo.png') }}" class="img-fluid" alt="">
							</a>
						</div>

						<!--=======  End of logo  =======-->
					</div>
					<div class="col-12 col-lg-6 col-md-5 mb-sm-20">
						<!--=======  Search bar  =======-->
						<form action="{{ route('catalog.index') }}" method="GET">
							<div class="search-bar">
								<input type="search" name="q" placeholder="Search entire store here ...">
								<button type="submit"><i class="lnr lnr-magnifier"></i></button>
							</div>
						</form>

						<!--=======  End of Search bar  =======-->
					</div>
					<div class="col-12 col-lg-3 col-md-4">
						@livewire('cart-wishlist-icons')
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
						@include('partials.category-menu')
					</div>
					<div class="col-12 col-lg-9">
						<!-- navigation section -->
						<div class="main-menu">
							<nav>
								<ul>
									<li><a href="{{ route('home') }}">Beranda</a></li>
									<li><a href="{{ route('catalog.index') }}">Toko</a></li>
									<li><a href="{{ route('page.show', ['slug' => 'contact']) }}">Kontak</a></li>
									<li><a href="{{ route('article.index') }}">Artikel</a></li>
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
	</div>

	<!--=====  End of Header One  ======-->


	<!--=============================================
	=            Hero Area One         =
	=============================================-->

	@yield('content')

	<!--=============================================
	=            Footer         =
	=============================================-->

	@livewire('footer')


	<!-- scroll to top  -->
	<a href="#" class="scroll-top"></a>
	<!-- end of scroll to top -->

	<!-- JS
	============================================ -->
	<!-- jQuery JS -->
	<script src="{{ asset('js/vendor/jquery.min.js') }}"></script>

	<!-- Toastr JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
	<script>
		// Configure Toastr
		toastr.options = {
			"closeButton": true,
			"debug": false,
			"newestOnTop": true,
			"progressBar": true,
			"positionClass": "toast-top-right",
			"preventDuplicates": false,
			"onclick": null,
			"showDuration": "300",
			"hideDuration": "1000",
			"timeOut": "5000",
			"extendedTimeOut": "1000",
			"showEasing": "swing",
			"hideEasing": "linear",
			"showMethod": "fadeIn",
			"hideMethod": "fadeOut"
		};
	</script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<!-- Popper JS -->
	<script src="{{ asset('js/popper.min.js') }}"></script>

	<!-- Bootstrap JS -->
	<script src="{{ asset('js/bootstrap.min.js') }}"></script>

	<!-- Plugins JS -->
	<script src="{{ asset('js/plugins.js') }}"></script>

	<!-- Main JS -->
	<script src="{{ asset('js/main.js') }}"></script>

	<!-- PTK Cart & Wishlist Handler -->
	<script src="{{ asset('js/ptk-cart-wishlist.js') }}"></script>

	@livewireScripts
    @stack('scripts')
</body>

</html>
