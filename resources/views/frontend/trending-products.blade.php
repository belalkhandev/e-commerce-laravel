@extends('layouts.frontend')

@section('title', __('Trending Products'))
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
						<h1>{{ __('Trending Products') }}</h1>
					</div>
				</div>
				<div class="col-lg-6">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
							<li class="breadcrumb-item active" aria-current="page">{{ __('Trending Products') }}</li>
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
				<div class="col-lg-12">
					<div class="row">
						@if(count($datalist)>0)
						@foreach ($datalist as $row)
						<div class="col-lg-3">
							<div class="item-card mb25">
								<div class="item-image">
									@if($row->labelname != '')
									<ul class="labels-list">
										<li><span class="tplabel" style="background:{{ $row->labelcolor }};">{{ $row->labelname }}</span></li>
									</ul>
									@endif
									<ul class="product-action">
										@if(($row->variation_color != '') || ($row->variation_size != ''))
										<li><a href="{{ route('frontend.product', [$row->id, $row->slug]) }}"><i class="bi bi-cart"></i></a></li>
										@else
										<li><a class="addtocart" data-id="{{ $row->id }}" href="javascript:void(0);"><i class="bi bi-cart"></i></a></li>
										@endif
										<li><a href="{{ route('frontend.product', [$row->id, $row->slug]) }}"><i class="bi bi-zoom-in"></i></a></li>
										<li><a class="addtowishlist" data-id="{{ $row->id }}" href="javascript:void(0);"><i class="bi bi-heart"></i></a></li>
									</ul>
									@if($row->variation_color != '')
									<ul class="color-list">
									  @foreach(explode(',', $row->variation_color) as $color)
									  @php $color_array = explode('|', $color); @endphp
										<li style="background:{{ $color_array[1] }};"></li>
									  @endforeach
									</ul>
									@endif
									<a href="{{ route('frontend.product', [$row->id, $row->slug]) }}"><img src="{{ asset('public/media/'.$row->f_thumbnail) }}" alt="{{ $row->title }}" /></a>
								</div>
								<h4 class="item-title"><a href="{{ route('frontend.product', [$row->id, $row->slug]) }}">{{ str_limit($row->title) }}</a></h4>
								<div class="brand-card">
									<div class="brand">
										<span>{{ __('Brand') }} <a href="{{ route('frontend.brand', [$row->brand_id, str_slug($row->brandname)]) }}">{{ str_limit($row->brandname) }}</a></span>
									</div>
									{{-- <div class="brand">
										<span>{{ __('Sold By') }} <a href="{{ route('frontend.stores', [$row->seller_id, str_slug($row->shop_url)]) }}">{{ str_limit($row->shop_name) }}</a></span>
									</div> --}}
								</div>
								<div class="item-price-card">
									@if($row->sale_price != '')
										@if($gtext['currency_position'] == 'left')
										<div class="item-price">{{ $gtext['currency_icon'] }}{{ number_format($row->sale_price) }}</div>
										@else
										<div class="item-price">{{ number_format($row->sale_price) }}{{ $gtext['currency_icon'] }}</div>
										@endif
									@endif
									@if($row->old_price != '')
										@if($gtext['currency_position'] == 'left')
										<div class="old-item-price">{{ $gtext['currency_icon'] }}{{ number_format($row->old_price) }}</div>
										@else
										<div class="old-item-price">{{ number_format($row->old_price) }}{{ $gtext['currency_icon'] }}</div>
										@endif
									@endif
								</div>
								<div class="rating-wrap">
									<div class="stars-outer">
										<div class="stars-inner" style="width:{{ $row->ReviewPercentage }}%;"></div>
									</div>
									<span class="rating-count">({{ $row->TotalReview }})</span>
								</div>
							</div>
						</div>
						@endforeach
						@else
						<div class="col-lg-12">
							<h5 class="text-center">{{ __('Oops! No product found.') }}</h5>
						</div>
						@endif
					</div>
					<div class="row mt-15">
						<div class="col-lg-12">
							{{ $datalist->links() }}
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
	<!-- /Category Page/ -->
@endsection

@push('scripts')

@endpush
