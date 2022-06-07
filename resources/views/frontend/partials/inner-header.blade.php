
	<!--Top Header-->
	<div class="top-header">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					@if($gtext['is_publish'] == 1)
					<ul class="top-contact">
						@if($gtext['address'] != '')
						<li><i class="bi bi-geo-alt"></i>{{ $gtext['address'] }}</li>
						@endif
						@if($gtext['phone'] != '')
						<li><i class="bi bi-telephone"></i>{{ $gtext['phone'] }}</li>
						@endif
					</ul>
					@endif
				</div>
				<div class="col-lg-6">
					<ul class="top-list">
						<li><a href="{{ route('frontend.order-tracking') }}"><i class="bi bi-geo"></i>{{ __('Order Tracking') }}</a></li>
						@if (Route::has('login'))
						@auth
						<li>
							<div class="btn-group language-menu">
								<a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
									{{ Auth::user()->name }}
								</a>
								<ul class="dropdown-menu dropdown-menu-end">
									<li><a class="dropdown-item" href="{{ route('frontend.my-dashboard') }}">{{ __('My Dashboard') }}</a></li>
									<li><a class="dropdown-item" href="{{ route('logout') }}"
									onclick="event.preventDefault();
									document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
									<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
										@csrf
									</form>
									</li>
								</ul>
							</div>
						</li>
						@else
						@if (Route::has('frontend.register'))
						<li><a href="{{ route('frontend.register') }}"><i class="bi bi-person-plus"></i>{{ __('Register') }}</a></li>
						@endif
						<li><a href="{{ route('frontend.login') }}"><i class="bi bi-person"></i>{{ __('Sign in') }}</a></li>
						@endauth
						@endif
						
						@if($gtext['is_language_switcher'] == 1)
						<li>
							@php echo language(); @endphp
						</li>
						@endif
					</ul>
				</div>
			</div>
		</div>
	</div><!--/Top Header/-->
	
	<!--Header-->
	<header id="sticky-header" class="header">
		<div class="header-area">
			<div class="container">
				<div class="navbar-content">
					<ul class="head-round-icon">
						<li class="off-canvas-btn"><a href="javascript:void(0);"><i class="bi bi-list"></i></a></li>
						<li class="off-canvas-btn"><a href="javascript:void(0);"><i class="bi bi-search"></i></a></li>
					</ul>
					<div class="navbar-logo">
						<a href="{{ url('/') }}">
							<img src="{{ $gtext['front_logo'] ? asset('public/media/'.$gtext['front_logo']) : asset('public/frontend/images/logo.png') }}" alt="logo">
						</a>
					</div>
					<div class="header-search">
						<form method="GET" action="{{ route('frontend.search') }}">
							<input name="search" type="text" class="form-control" placeholder="{{ __('Search for Products') }}" required />
							<button type="submit" class="btn btn-search"><i class="bi bi-search"></i>{{ __('Search') }}</button>
						</form>
					</div>
					<ul class="head-round-icon">
						<li>
							<a href="{{ route('frontend.wishlist') }}">
								<i class="bi bi-heart"></i>
								<span class="cart_count count_wishlist">0</span>
							</a>
						</li>
						<li>
							<a href="javascript:void(0);" class="sidebar_show_hide">
								<i class="bi bi-cart"></i>
								<span class="cart_count total_qty">0</span>
							</a>
						</li>
					</ul>	
				</div>
			</div>
		</div>
	</header><!--/Header/-->
	
	<!--Menu-->
	<div class="header-menu">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="tp-mega-full">
						<div class="tp-menu align-self-center">
							<nav>
								<ul>@php echo HeaderMenuList('HeaderMenuListForDesktop'); @endphp</ul>
							</nav>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div><!--/Menu/-->

	<!-- off-canvas menu start -->
	<aside class="mobile-menu-wrapper">
		<div class="off-canvas-overlay"></div>
		<div class="offcanvas-body">
			<div class="offcanvas-top">
				<div class="offcanvas-btn-close">
					<i class="bi bi-x-lg"></i>
				</div>
			</div>
			<div class="search-for-mobile">
				<form method="GET" action="{{ route('frontend.search') }}">
					<input name="search" type="text" class="form-control" placeholder="{{ __('Search for Products') }}" required />
					<button type="submit" class="btn theme-btn"><i class="bi bi-search"></i>{{ __('Search') }}</button>
				</form>	
			</div>
			<div class="mobile-navigation">
				<nav>
					<ul class="mobile-menu">
						<li class="has-children-menu"><a href="#">{{ __('Top Categories') }}</a>
							<ul class="dropdown">
								@php echo CategoryMenuList(); @endphp
							</ul>
						</li>
						@php echo HeaderMenuList('HeaderMenuListForMobile'); @endphp
					</ul>
				</nav>
			</div>
		</div>
	</aside>
	<!-- /off-canvas menu start -->
	