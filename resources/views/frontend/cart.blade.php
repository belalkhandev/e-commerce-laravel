@extends('layouts.frontend')

@section('title', __('Cart'))
@php 
$gtext = gtext(); 
$gtax = getTax();
@endphp

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
						<h1>{{ __('Cart') }}</h1>
					</div>
				</div>
				<div class="col-lg-6">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
							<li class="breadcrumb-item active" aria-current="page">{{ __('Cart') }}</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
	<!-- /Page Breadcrumb/ -->
	<!-- Cart -->
	<div class="inner-section">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="table-responsive shopping-cart">
						<table class="table">
							<thead>
								<tr>
									<th>{{ __('Image') }}</th>
									<th>{{ __('Product') }}</th>
									<th>{{ __('Variation') }}</th>
									<th class="text-center">{{ __('Price') }}</th>
									<th class="text-center">{{ __('Quantity') }}</th>
									<th class="text-center">{{ __('Total') }}</th>
									<th class="text-center">{{ __('Remove') }}</th>
								</tr>
							</thead>
							<tbody id="tp_viewcart_datalist"></tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-7"></div>
				<div class="col-lg-5 mt10">
					<div class="carttotals-card">
						<div class="carttotals-head">{{ __('Cart Total') }}</div>
						<div class="carttotals-body">
							<table class="table">
								<tbody>
									<tr><td><span class="title">{{ __('Price Total') }}</span><span class="price viewcart_price_total"></span></td></tr>
									<tr><td><span class="title">{{ __('Tax') }}</span><span class="price viewcart_tax"></span></td></tr>
									<tr><td><span class="title">{{ __('Subtotal') }}</span><span class="price viewcart_sub_total"></span></td></tr>
									<tr><td><span class="total">{{ __('Total') }}</span><span class="total-price viewcart_total"></span></td></tr>
								</tbody>
							</table>
							<a class="btn theme-btn mt10" href="{{ route('frontend.checkout') }}">{{ __('Proceed To CheckOut') }}</a>
						</div>
					</div>
				</div>
			</div>			
		</div>
	</div>
	<!-- /Cart/ -->
@endsection

@push('scripts')
<script src="{{asset('public/frontend/pages/view_cart.js')}}"></script>
@endpush	