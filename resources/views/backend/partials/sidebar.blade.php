<div class="sidebar-wrapper">
	<div class="logo">
		<a href="{{ route('backend.dashboard') }}">
			<img src="{{ $gtext['back_logo'] ? asset('public/media/'.$gtext['back_logo']) : asset('public/backend/images/backend-logo.png') }}" alt="logo">
		</a>
	</div>
	<ul class="left-navbar">
		@if (Auth::user()->role_id == 1)
		<li><a href="{{ route('backend.dashboard') }}"><i class="fa fa-tachometer"></i>{{ __('Dashboard') }}</a></li>
		<li><a href="{{ route('backend.media') }}"><i class="fa fa-picture-o"></i>{{ __('Media') }}</a></li>
		<li><a href="{{ route('backend.page') }}"><i class="fa fa-clipboard"></i>{{ __('Pages') }}</a></li>
		<li><a href="{{ route('backend.orders') }}" id="select_orders"><i class="fa fa-rocket"></i>{{ __('Orders') }}</a></li>
		<li class="dnone"><a href="{{ route('backend.transactions') }}"><i class="fa fa-credit-card"></i>{{ __('Transactions') }}</a></li>
		<li class="dropdown"><a class="nav-link has-dropdown" href="#" data-toggle="dropdown"><i class="fa fa-shopping-cart"></i>{{ __('eCommerce') }}</a>
			<ul class="dropdown-menu">
				<li><a href="{{ route('backend.products') }}">{{ __('Products') }}</a></li>
				<li><a href="{{ route('backend.manage-stock') }}">{{ __('Manage Stock') }}</a></li>
				<li><a href="{{ route('backend.product-categories') }}">{{ __('Product Categories') }}</a></li>
				<li><a href="{{ route('backend.brands') }}">{{ __('Brands') }}</a></li>
				<li><a href="{{ route('backend.shipping') }}">{{ __('Shipping') }}</a></li>
				<li><a href="{{ route('backend.collections') }}">{{ __('Collections') }}</a></li>
				<li><a href="{{ route('backend.attributes') }}">{{ __('Attributes') }}</a></li>
				<li><a href="{{ route('backend.labels') }}">{{ __('Labels') }}</a></li>
				<li class="dnone"><a href="{{ route('backend.coupons') }}">{{ __('Coupons') }}</a></li>
				<li><a href="{{ route('backend.tax') }}">{{ __('Tax') }}</a></li>
				<li><a href="{{ route('backend.currency') }}">{{ __('Currency') }}</a></li>
				<li><a href="{{ route('backend.slider') }}">{{ __('Home Slider') }}</a></li>
				<li><a href="{{ route('backend.offer-ads') }}">{{ __('Offer & Ads') }}</a></li>
				<li><a href="{{ route('backend.trending') }}">{{ __('Trending') }}</a></li>
			</ul>
		</li>
		<li class="dropdown"><a class="nav-link has-dropdown" href="#" data-toggle="dropdown"><i class="fa fa-wrench"></i>{{ __('Appearance') }}</a>
			<ul class="dropdown-menu">
				<li><a href="{{ route('backend.menu') }}">{{ __('Menu') }}</a></li>
				<li><a href="{{ route('backend.theme-options') }}">{{ __('Theme Options') }}</a></li>
			</ul>
		</li>
		<li class="dropdown"><a class="nav-link has-dropdown" href="#" data-toggle="dropdown"><i class="fa fa-sitemap"></i>{{ __('Marketplace') }}</a>
			<ul class="dropdown-menu">
				<li><a href="{{ route('backend.sellers') }}">{{ __('Sellers') }}</a></li>
				<li><a href="{{ route('backend.withdrawals') }}">{{ __('Withdrawals') }}</a></li>
				<li><a href="{{ route('backend.seller-settings') }}">{{ __('Settings') }}</a></li>
			</ul>
		</li>
		<li><a href="{{ route('backend.customers') }}"><i class="fa fa-users"></i>{{ __('Customers') }}</a></li>
		<li><a href="{{ route('backend.review') }}"><i class="fa fa-recycle"></i>{{ __('Review & Ratings') }}</a></li>

		<li class="dropdown"><a class="nav-link has-dropdown" href="#" data-toggle="dropdown"><i class="fa fa-paper-plane"></i>{{ __('Newsletters') }}</a>
			<ul class="dropdown-menu">
				<li><a href="{{ route('backend.subscribers') }}">{{ __('Subscribers') }}</a></li>
				<li><a href="{{ route('backend.subscribe-settings') }}">{{ __('Subscribe Settings') }}</a></li>
				<li><a href="{{ route('backend.mailchimp-settings') }}">{{ __('MailChimp Settings') }}</a></li>
			</ul>
		</li>
		<li class="dropdown"><a class="nav-link has-dropdown" href="#" data-toggle="dropdown"><i class="fa fa-language"></i>{{ __('Languages') }}</a>
			<ul class="dropdown-menu">
				<li><a href="{{ route('backend.languages') }}">{{ __('Languages') }}</a></li>
				<li><a href="{{ route('backend.language-keywords') }}">{{ __('Language Keywords') }}</a></li>
			</ul>
		</li>
		<li><a id="active-settings" href="{{ route('backend.general') }}"><i class="fa fa-cogs"></i>{{ __('Settings') }}</a></li>
		<li><a href="{{ route('backend.users') }}"><i class="fa fa-user-plus"></i>{{ __('Users') }}</a></li>
		@elseif (Auth::user()->role_id == 3)
		<li><a href="{{ route('seller.dashboard') }}"><i class="fa fa-tachometer"></i>{{ __('Dashboard') }}</a></li>
		<li><a href="{{ route('seller.products') }}" id="select_product"><i class="fa fa-product-hunt"></i>{{ __('Products') }}</a></li>
		<li><a href="{{ route('seller.orders') }}" id="select_order"><i class="fa fa-rocket"></i>{{ __('Orders') }}</a></li>
		<li><a href="{{ route('seller.withdrawals') }}"><i class="fa fa-rocket"></i>{{ __('Withdrawals') }}</a></li>
		<li><a href="{{ route('seller.review') }}"><i class="fa fa-recycle"></i>{{ __('Review & Ratings') }}</a></li>
		<li><a href="{{ route('seller.settings') }}"><i class="fa fa-cogs"></i>{{ __('Settings') }}</a></li>
		<li><a href="{{ route('frontend.my-dashboard') }}"><i class="fa fa-bandcamp"></i>{{ __('Customer Dashboard') }}</a></li>
		@endif
	</ul>
</div>