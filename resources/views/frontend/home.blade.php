@extends('layouts.frontend')

@section('title', __('Home'))
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
@include('frontend.partials.header')
@endsection

@section('content')
	<!-- Home Slider -->
	<div class="slider-section">
		<div class="container">
			<div class="row">
				<div class="col-lg-9 offset-lg-3">
					<div class="home-slider owl-carousel">
						@foreach ($slider as $row)
						<div class="slider-item">
							<a href="{{ $row->url }}"><img src="{{ asset('public/media/'.$row->image) }}" alt="" /></a>
						</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /Home Slider/ -->

	<!-- Banner Item -->
	<div class="section">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 col-md-12 col-lg-4 col-xl-4 col-xxl-4">
					@if($position_1['is_publish'] == 1)
					<div class="banner-item mb25">
						<div class="banner-item-img">
							<img src="{{ asset('public/media/'.$position_1['image']) }}" alt="{{ $position_1['text_1'] }}"/>
						</div>
						<div class="banner-item-info">
							<h2>{{ $position_1['text_1'] }}</h2>
							<h4>{{ $position_1['text_2'] }}</h4>
							<a class="btn theme-btn" href="{{ $position_1['url'] }}">{{ __('Shop Now') }}</a>
						</div>
					</div>
					@endif
					@if($position_2['is_publish'] == 1)
					<div class="banner-item mb25">
						<div class="banner-item-img">
							<img src="{{ asset('public/media/'.$position_2['image']) }}" alt="{{ $position_2['text_1'] }}"/>
						</div>
						<div class="banner-item-info">
							<h2>{{ $position_2['text_1'] }}</h2>
							<h4>{{ $position_2['text_2'] }}</h4>
							<a class="btn theme-btn" href="{{ $position_2['url'] }}">{{ __('Shop Now') }}</a>
						</div>
					</div>
					@endif
				</div>
				@if($position_3['is_publish'] == 1)
				<div class="col-sm-12 col-md-12 col-lg-4 col-xl-4 col-xxl-4">
					<div class="banner-item mb25">
						<div class="banner-item-img">
							<img src="{{ asset('public/media/'.$position_3['image']) }}" alt="{{ $position_3['text_1'] }}"/>
						</div>
						<div class="banner-item-info">
							<h2>{{ $position_3['text_1'] }}</h2>
							<h4>{{ $position_3['text_2'] }}</h4>
							<a class="btn theme-btn" href="{{ $position_3['url'] }}">{{ __('Shop Now') }}</a>
						</div>
					</div>
				</div>
				@endif
				<div class="col-sm-12 col-md-12 col-lg-4 col-xl-4 col-xxl-4">
					@if($position_4['is_publish'] == 1)
					<div class="banner-item mb25">
						<div class="banner-item-img">
							<img src="{{ asset('public/media/'.$position_4['image']) }}" alt="{{ $position_4['text_1'] }}"/>
						</div>
						<div class="banner-item-info">
							<h2>{{ $position_4['text_1'] }}</h2>
							<h4>{{ $position_4['text_2'] }}</h4>
							<a class="btn theme-btn" href="{{ $position_4['url'] }}">{{ __('Shop Now') }}</a>
						</div>
					</div>
					@endif
					@if($position_5['is_publish'] == 1)
					<div class="banner-item mb25">
						<div class="banner-item-img">
							<img src="{{ asset('public/media/'.$position_5['image']) }}" alt="{{ $position_5['text_1'] }}"/>
						</div>
						<div class="banner-item-info">
							<h2>{{ $position_5['text_1'] }}</h2>
							<h4>{{ $position_5['text_2'] }}</h4>
							<a class="btn theme-btn" href="{{ $position_5['url'] }}">{{ __('Shop Now') }}</a>
						</div>
					</div>
					@endif
				</div>
			</div>
		</div>
	</div>
	<!-- /Banner Item/ -->

	<!--Brand Slider-->
	<div class="section">
		<div class="container">
			<div class="row">
				<div class="section-heading">
					<h3 class="title">{{ __('Shop by Brands') }}</h3>
				</div>
			</div>
			<div class="row owl-carousel caro-common brands-carousel">
				@foreach ($brand as $row)
				<div class="col-lg-12">
					<div class="brand-card">
						<a href="{{ route('frontend.brand', [$row->id, str_slug($row->name)]) }}"><img src="{{ asset('public/media/'.$row->thumbnail) }}" alt="{{ $row->name }}"/></a>
					</div>
				</div>
				@endforeach
			</div>
		</div>
	</div>
	<!--/Brand Slider/-->

	<!-- New Arrivals Section -->
	<div class="section">
		<div class="container">
			<div class="row">
				<div class="section-heading">
					<h3 class="title">{{ __('New Arrivals') }}</h3>
					<a class="btn theme-btn seeall-btn" href="{{ route('frontend.new-arrivals') }}">{{ __('See all') }}</a>
				</div>
			</div>

			<div class="row owl-carousel caro-common category-carousel">
				@foreach ($new_arrivals as $row)
				<div class="col-lg-12">
					<div class="item-card mb30">
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
							<a href="{{ route('frontend.product', [$row->id, $row->slug]) }}"><img src="{{ asset('public/media/'.$row->f_thumbnail) }}" alt="{{ $row->title }}"/></a>
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
					</div>
				</div>
				@endforeach
			</div>
		</div>
	</div>
	<!-- /New Arrivals Section/ -->

	<!-- Trending Products Section -->
	<div class="section">
		<div class="container">
			<div class="row">
				<div class="section-heading">
					<h3 class="title">{{ __('Trending Products') }}</h3>
					<a class="btn theme-btn seeall-btn" href="{{ route('frontend.trending-products') }}">{{ __('See all') }}</a>
				</div>
			</div>
			<div class="row owl-carousel caro-common category-carousel">
				@foreach ($trending_products as $row)
				<div class="col-lg-12">
					<div class="item-card mb30">
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
							<a href="{{ route('frontend.product', [$row->id, $row->slug]) }}"><img src="{{ asset('public/media/'.$row->f_thumbnail) }}" alt="{{ $row->title }}"/></a>
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
			</div>
		</div>
	</div>
	<!-- /Trending Products Section/ -->

	<!-- Best Sellers Section -->
	<div class="section">
		<div class="container">
			<div class="row">
				<div class="section-heading">
					<h3 class="title">{{ __('Best Sellers') }}</h3>
					<a class="btn theme-btn seeall-btn" href="{{ route('frontend.best-sellers') }}">{{ __('See all') }}</a>
				</div>
			</div>
			<div class="row owl-carousel caro-common category-carousel">
				@foreach ($best_sellers as $row)
				<div class="col-lg-12">
					<div class="item-card mb30">
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
							<a href="{{ route('frontend.product', [$row->id, $row->slug]) }}"><img src="{{ asset('public/media/'.$row->f_thumbnail) }}" alt="{{ $row->title }}"/></a>
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
			</div>
		</div>
	</div>
	<!-- /Best Sellers Section/ -->

	<!-- Available Offer Section -->
	<div class="section">
		<div class="container">
			<div class="row">
				<div class="section-heading">
					<h3 class="title">{{ __('Available Offer') }}</h3>
					<a class="btn theme-btn seeall-btn" href="{{ route('frontend.available-offer') }}">{{ __('See all') }}</a>
				</div>
			</div>
			<div class="row owl-carousel caro-common category-carousel">
				@foreach ($available_offer as $row)
				<div class="col-lg-12">
					<div class="item-card mb30">
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
							<a href="{{ route('frontend.product', [$row->id, $row->slug]) }}"><img src="{{ asset('public/media/'.$row->f_thumbnail) }}" alt="{{ $row->title }}"/></a>
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
			</div>
		</div>
	</div>
	<!-- /Available Offer Section/ -->

	<!--Add Part-->
	@if($trending_data['is_publish'] == 1)
	<div class="add-part-section">
		<div class="add-bg" style="background-image:url({{ asset('public/media/'.$trending_data['image']) }})">
			<div class="container">
				<div class="row">
					<div class="col-lg-4 offset-lg-4 col-md-12 col-sm-12 col-12">
						<div class="add-card">
							<h2>{{ $trending_data['title'] }}</h2>
							@if($trending_data['short_desc'] !='')
							<p>{{ $trending_data['short_desc'] }}</p>
							@endif
							<a class="btn theme-btn" href="{{ $trending_data['url'] }}">{{ __('Shop Now') }}</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@endif
	<!-- /Add Part/ -->

	@if(Session::has('subscribePopupOff'))
	@else
		@if($gtext['is_subscribe_popup'] == 1)
		<div class="modal fade" id="subscribe_popup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content newsletter-card">
					<div class="modal-header newsletter-header">
						<button onclick="popup_modal_close()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body newsletter-body">
						<h4>{{ __('Subscribe our newsletter') }}</h4>
						<p class="mb20">{{ $gtext['subscribe_popup_desc'] }}</p>
						<div class="newsletter-form">
							<input name="newsletter_email" id="newsletter_email" type="email" placeholder="{{ __('Enter your email address') }}" />
							<a class="btn theme-btn mt10 full newsletter_btn nletter_btn" href="javascript:void(0);">{{ __('Submit') }}</a>
							<div class="newsletter_msg mt5"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		@endif
	@endif
@endsection

@push('scripts')

@if(Session::has('subscribePopupOff'))
@else
	@if($gtext['is_subscribe_popup'] == 1)
	<script type="text/javascript">
	(function ($) {
		'use strict';
		var subscribePopupModal = new bootstrap.Modal(document.getElementById('subscribe_popup'), {
		  keyboard: false
		});

		subscribePopupModal.show();

		//Subscribe for page
		$(document).on("click", ".newsletter_btn", function(event) {
			event.preventDefault();

			var newsletterEmail = $("#newsletter_email").val();
			var status = 'subscribed';

			var nletter_btn = $('.nletter_btn').html();
			var newsletter_recordid = '';

			var newsletter_email = newsletterEmail.trim();

			if(newsletter_email == ''){
				$('.newsletter_msg').html('<p class="text-danger">The email address field is required.</p>');
				return;
			}

			$.ajax({
				type : 'POST',
				url: base_url + '/frontend/saveSubscriber',
				data: 'RecordId=' + newsletter_recordid+'&email_address='+newsletter_email+'&status='+status,
				beforeSend: function() {
					$('.newsletter_msg').html('');
					$('.nletter_btn').html('<span class="spinner-border spinner-border-sm"></span> Please Wait...');
				},
				success: function (response) {
					var msgType = response.msgType;
					var msg = response.msg;

					if (msgType == "success") {
						popup_modal_close();
						subscribePopupModal.hide();
						onSuccessMsg(msg);
					} else {
						$('.newsletter_msg').html('<p class="text-danger">'+msg+'</p>');
					}

					$('.nletter_btn').html(nletter_btn);
				}
			});
		});
	}(jQuery));

	function popup_modal_close() {
		$.ajax({
			type : 'POST',
			url: base_url + '/frontend/subscribePopupOff',
			data: 'PopupOff=OFF',
			success: function (response){}
		});
	}
	</script>
	@endif
@endif

@endpush
