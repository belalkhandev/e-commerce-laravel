@extends('layouts.frontend')

@section('title', __('login'))
@php $gtext = gtext(); @endphp

@section('meta-content')
	<meta name="keywords" content="{{ $gtext['og_keywords'] }}" />
	<meta name="description" content="{{ $gtext['og_description'] }}" />
	<meta property="og:title" content="{{ $gtext['og_title'] }}" />
	<meta property="og:site_name" content="{{ $gtext['site_name'] }}" />
	<meta property="og:description" content="{{ $gtext['og_description'] }}" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="{{ url()->current() }}" />
	<meta property="og:image" content="{{ asset('public/media/'.$gtext['og_image']) }}" />
	<meta property="og:image:width" content="600" />
	<meta property="og:image:height" content="315" />
	@if($gtext['fb_publish'] == 1)
	<meta name="fb:app_id" property="fb:app_id" content="{{ $gtext['fb_app_id'] }}" />
	@endif
	<meta name="twitter:card" content="summary_large_image">
	@if($gtext['twitter_publish'] == 1)
	<meta name="twitter:site" content="{{ $gtext['twitter_id'] }}">
	<meta name="twitter:creator" content="{{ $gtext['twitter_id'] }}">
	@endif
	<meta name="twitter:url" content="{{ url()->current() }}">
	<meta name="twitter:title" content="{{ $gtext['og_title'] }}">
	<meta name="twitter:description" content="{{ $gtext['og_description'] }}">
	<meta name="twitter:image" content="{{ asset('public/media/'.$gtext['og_image']) }}">
@endsection

@section('header')
@include('frontend.partials.inner-header')
@endsection

@section('content')
	<!-- Page Breadcrumb -->
	<div class="breadcrumb-section">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6">
					<div class="page-title">
						<h1>{{ __('login') }}</h1>
					</div>
				</div>
				<div class="col-lg-6">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
							<li class="breadcrumb-item active" aria-current="page">{{ __('login') }}</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
	<!-- /Page Breadcrumb/ -->
	<!-- Category Page -->
	<div class="section">
		<div class="container">
			<div class="row">
				<div class="col-md-4 offset-md-4">
					<div class="register">
						<h4>{{ __('login') }}</h4>
						<p>{{ __('Please enter your email address and password') }}</p>

						@if(Session::has('fail'))
						<div class="alert alert-danger">
							{{Session::get('fail')}}
						</div>
						@endif
						@if (session('message'))
							<div class="alert alert-danger">{{ session('message') }}</div>
						@endif
						<form class="form" method="POST" action="{{ route('frontend.customer-login') }}">
							@csrf
							<div class="form-group">
								<input name="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('Email Address') }}" value="{{ old('email') }}" required />
                                @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
							</div>
							<div class="form-group">
								<input name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('Password') }}" required />
                                @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
							</div>
							<div class="tw_checkbox checkbox_group">
								<input id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
								<label for="remember">{{ __('Remember me') }}</label>
								<span></span>
							</div>
							<input type="submit" class="btn theme-btn full" value="{{ __('login') }}">
						</form>
						@if (Route::has('frontend.reset'))
						<h3><a href="{{ route('frontend.reset') }}">{{ __('Forgot your password?') }}</a></h3>
						@endif
						@if (Route::has('frontend.register'))
						<h3><a href="{{ route('frontend.register') }}">{{ __('Sign up for an account') }}</a></h3>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /Category Page/ -->
@endsection

@push('scripts')

@endpush	