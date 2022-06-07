@extends('layouts.frontend')

@section('title', __('Register'))
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
						<h1>{{ __('Register') }}</h1>
					</div>
				</div>
				<div class="col-lg-6">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
							<li class="breadcrumb-item active" aria-current="page">{{ __('Register') }}</li>
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
					<div class="row mt10 mb5">
						<div class="col-md-12">
							<a href="{{ route('frontend.register') }}" class="btn white-btn text-initial mr10 font-bold">{{ __('I am a customer') }}</a>
							<a href="{{ route('frontend.seller-register') }}" class="btn white-btn text-initial font-bold active">{{ __('I am a seller') }}</a>
						</div>
					</div>
					<div class="register">
						<h4>{{ __('Create an seller account') }}</h4>
						<p>{{ __('Please fill in the information below') }}</p>
						
						@if(Session::has('success'))
						<div class="alert alert-success">
							{{Session::get('success')}}
						</div>
						@endif
						@if(Session::has('fail'))
						<div class="alert alert-danger">
							{{Session::get('fail')}}
						</div>
						@endif
						<form class="form" method="POST" action="{{ route('frontend.sellerRegister') }}">
							@csrf
							<div class="form-group">
								<input name="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="{{ __('Name') }}" value="{{ old('name') }}" required />
                                @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
							</div>
							
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
							
							<div class="form-group">
								<input name="password_confirmation" type="password" class="form-control" placeholder="{{ __('Confirm password') }}" required >
							</div>
							
							<div class="form-group">
								<input name="shop_name" type="text" class="form-control @error('shop_name') is-invalid @enderror" placeholder="{{ __('Shop Name') }}" value="{{ old('shop_name') }}" required />
                                @if ($errors->has('shop_name'))
                                <span class="text-danger">{{ $errors->first('shop_name') }}</span>
                                @endif
							</div>
							
							<div class="form-group">
								<input name="shop_url" id="shop_url" type="text" class="form-control @error('shop_url') is-invalid @enderror" placeholder="{{ __('Shop URL') }}" value="{{ old('shop_url') }}" required />
								@if ($errors->has('shop_url'))
                                <span class="text-danger">{{ $errors->first('shop_url') }}</span>
                                @endif
							</div>
							
							<div class="form-group">
								<input name="shop_phone" type="text" class="form-control @error('shop_phone') is-invalid @enderror" placeholder="{{ __('Shop Phone') }}" value="{{ old('shop_phone') }}" required />
                                @if ($errors->has('shop_phone'))
                                <span class="text-danger">{{ $errors->first('shop_phone') }}</span>
                                @endif
							</div>
							
							@if($gtext['is_recaptcha'] == 1)
							<div class="form-group">
								<div class="g-recaptcha" data-sitekey="{{ $gtext['sitekey'] }}"></div>
                                @if ($errors->has('g-recaptcha-response'))
                                <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                                @endif
							</div>
							@endif
							<input type="submit" class="btn theme-btn full" value="{{ __('Register') }}">
						</form>
						@if (Route::has('frontend.reset'))
						<h3><a href="{{ route('frontend.reset') }}">{{ __('Forgot your password?') }}</a></h3>
						@endif
						@if (Route::has('frontend.login'))
						<h3><a href="{{ route('frontend.login') }}">{{ __('Back to login') }}</a></h3>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /Category Page/ -->
@endsection

@push('scripts')
@if($gtext['is_recaptcha'] == 1)
<script src='https://www.google.com/recaptcha/api.js' async defer></script>
@endif
<script>
$("#shop_url").on("blur", function () {
	var shop_url = $("#shop_url").val();
	var str_name = shop_url.trim();
	var strLength = str_name.length;
	if(strLength>0){
		$.ajax({
			type : 'POST',
			url: base_url + '/frontend/hasShopSlug',
			data: 'shop_url='+shop_url,
			success: function (response) {
				var slug = response.slug;
				$("#shop_url").val(slug);
			}
		});
	}
});
</script>
@endpush	