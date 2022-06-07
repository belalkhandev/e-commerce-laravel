@extends('layouts.frontend')

@section('title', __('Profile'))
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
						<h1>{{ __('Profile') }}</h1>
					</div>
				</div>
				<div class="col-lg-6">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
							<li class="breadcrumb-item active" aria-current="page">{{ __('Profile') }}</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
	<!-- /Page Breadcrumb/ -->
	<!-- My Dashboard -->
	<div class="my-dashbord">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 col-md-4 col-lg-3 col-xl-3">
					@include('frontend.partials.my-dashbord-sidebar')
				</div>
				<div class="col-sm-12 col-md-8 col-lg-9 col-xl-9">
					<div class="my_card">
						<div class="row">
							<div class="col-lg-12">
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
								<form method="POST" action="{{ route('frontend.UpdateProfile') }}">
									@csrf
									<div class="row">
										<div class="col-md-12">
											<div class="mb-3">
												<label for="name">{{ __('Name') }}<span class="red">*</span></label>
												<input id="name" name="name" type="text" class="form-control" placeholder="{{ __('Name') }}" value="@if(isset(Auth::user()->name)) {{ Auth::user()->name }} @endif" required />
												@if ($errors->has('name'))
												<span class="text-danger">{{ $errors->first('name') }}</span>
												@endif
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="mb-3">
												<label for="email">{{ __('Email Address') }}<span class="red">*</span></label>
												<input id="email" name="email" type="email" class="form-control" placeholder="{{ __('Email Address') }}" value="@if(isset(Auth::user()->email)) {{ Auth::user()->email }} @endif" required readonly />
												@if ($errors->has('email'))
												<span class="text-danger">{{ $errors->first('email') }}</span>
												@endif
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="mb-3">
												<label for="phone">{{ __('Phone') }}</label>
												<input id="phone" name="phone" type="text" class="form-control" placeholder="{{ __('Phone') }}" value="@if(isset(Auth::user()->phone)) {{ Auth::user()->phone }} @endif" />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="mb-3">
												<label for="address">{{ __('Address') }}</label>
												<textarea id="address" name="address" class="form-control" placeholder="{{ __('Address') }}" rows="4">@if(isset(Auth::user()->address)) {{ Auth::user()->address }} @endif</textarea>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											@if($gtext['is_recaptcha'] == 1)
											<div class="mb-3">
												<div class="g-recaptcha" data-sitekey="{{ $gtext['sitekey'] }}"></div>
												@if ($errors->has('g-recaptcha-response'))
												<span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
												@endif
											</div>
											@endif
										</div>
									</div>
									<input name="user_id" type="hidden" value="@if(isset(Auth::user()->id)) {{ Auth::user()->id }} @endif" />
									<input type="submit" class="btn theme-btn" value="{{ __('Update') }}" />
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /My Dashboard/ -->
@endsection

@push('scripts')
@if($gtext['is_recaptcha'] == 1)
<script src='https://www.google.com/recaptcha/api.js' async defer></script>
@endif
<script type="text/javascript">
	var my_dashbord_href = location.href;
	var my_dashbord_elem = '.sidebar-nav li a[href="' + my_dashbord_href + '"]';
	$('ul.sidebar-nav li').parent().removeClass('active');
	$('ul.sidebar-nav li a').parent().removeClass('active');
	$(my_dashbord_elem).addClass('active');
</script>
@endpush	