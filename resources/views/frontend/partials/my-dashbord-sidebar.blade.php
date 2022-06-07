<div class="dashbord-sidebar">
	<div class="profile-info">
		@if(isset(Auth::user()->id))
		<div class="avatar">{{ sub_str(Auth::user()->name, 0,1) }}</div>
		<h5>{{ Auth::user()->name }}</h5>
		<p>{{ Auth::user()->email }}</p>
		@endif
	</div>
	<div class="sidebar-nav">
		<ul>
			<li><a href="{{ route('frontend.my-dashboard') }}"><i class="bi bi-speedometer"></i>{{ __('Dashboard') }}</a></li>
			<li><a href="{{ route('frontend.my-orders') }}"><i class="bi bi-basket2-fill"></i>{{ __('Orders') }}</a></li>
			<li><a href="{{ route('frontend.my-profile') }}"><i class="bi bi-person"></i>{{ __('Profile') }}</a></li>
			<li><a href="{{ route('frontend.change-password') }}"><i class="bi bi-lock"></i>{{ __('Change Password') }}</a></li>
			@if(isset(Auth::user()->role_id))
				@if(Auth::user()->role_id == 3)
					<li><a href="{{ route('seller.dashboard') }}"><i class="bi bi-stack"></i>{{ __('Seller Dashboard') }}</a></li>
				@endif
			@endif
			<li><a  href="{{ route('logout') }}"
				onclick="event.preventDefault();
				document.getElementById('my-logout-form').submit();"><i class="bi bi-box-arrow-right"></i>{{ __('Logout') }}</a>
				<form id="my-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
					@csrf
				</form>
			</li>
		</ul>
	</div>
</div>