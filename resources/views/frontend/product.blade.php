@extends('layouts.frontend')

@section('title', $data->title)
@php $gtext = gtext(); @endphp

@section('meta-content')
	<meta name="keywords" content="{{ $data->og_keywords }}" />
	<meta name="description" content="{{ $data->og_description }}" />
	<meta property="og:title" content="{{ $data->og_title }}" />
	<meta property="og:site_name" content="{{ $gtext['site_name'] }}" />
	<meta property="og:description" content="{{ $data->og_description }}" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="{{ url()->current() }}" />
	<meta property="og:image" content="{{ asset('public/media/'.$data->og_image) }}" />
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
	<meta name="twitter:title" content="{{ $data->og_title }}">
	<meta name="twitter:description" content="{{ $data->og_description }}">
	<meta name="twitter:image" content="{{ asset('public/media/'.$data->og_image) }}">
@endsection

@section('header')
@include('frontend.partials.inner-header')
@endsection

@section('content')

<!-- Page Breadcrumb -->
<div class="breadcrumb-section">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-5">
				<div class="page-title">
					<h1>{{ $data->title }}</h1>
				</div>
			</div>
			<div class="col-lg-7">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
						<li class="breadcrumb-item" aria-current="page"><a href="{{ route('frontend.product-category', [$data->cat_id, $data->cat_slug]) }}">{{ $data->cat_name }}</a></li>
						<li class="breadcrumb-item active" aria-current="page">{{ $data->title }}</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</div>
<!-- /Page Breadcrumb/ -->
<!-- Product Details -->
<div class="inner-section">
	<div class="container">
		<!-- Single Product -->
		<div class="row">
			<div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-5 col-xxl-5 mb25">
				@if(count($pro_images)>0)
				<div id="product_big" class="single-product-slider owl-carousel caro-single-product">
					@foreach ($pro_images as $row)
					<div class="item">
						<img src="{{ asset('public/media/'.$row->large_image) }}" alt=""/>
					</div>
					@endforeach
				</div>
				<div id="product_thumbs" class="thumbnail-card owl-carousel">
					@foreach ($pro_images as $row)
					<div class="item">
						<img src="{{ asset('public/media/'.$row->thumbnail) }}" alt=""/>
					</div>
					@endforeach
				</div>
				@else
				<div id="product_big" class="single-product-slider owl-carousel caro-single-product">
					<div class="item">
						<img src="{{ asset('public/media/'.$data->f_thumbnail) }}" alt=""/>
					</div>
				</div>
				<div id="product_thumbs" class="thumbnail-card owl-carousel">
					<div class="item">
						<img src="{{ asset('public/media/'.$data->f_thumbnail) }}" alt=""/>
					</div>
				</div>
				@endif
			</div>
			
			<div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-7 col-xxl-7 mb25">
				<div class="pr_details">
					<h4 class="product_title">{{ $data->title }}</h4>
					<div class="pr_rating_wrap">
						<div class="rating-wrap">
							<div class="stars-outer">
								<div class="stars-inner" style="width:{{ $data->ReviewPercentage }}%;"></div>
							</div>
							<span class="rating-count">({{ $data->TotalReview }} {{ __('Review') }})</span>
						</div>
					</div>
					
					@if($data->short_desc != '')
					<div class="pr_extra">{{ $data->short_desc }}</div>
					@endif
					
					@if($data->brandname != '')
					<div class="pr_extra"><strong>{{ __('Brand') }}:</strong><a href="{{ route('frontend.brand', [$data->brand_id, str_slug($data->brandname)]) }}">  {{ $data->brandname }}</a></div>
					@endif
					
					@if($data->shop_name != '')
					<div class="pr_extra"><strong>{{ __('Sold By') }}:</strong><a href="{{ route('frontend.stores', [$data->seller_id, str_slug($data->shop_url)]) }}">  {{ $data->shop_name }}</a></div>
					@endif
					
					@if($data->is_stock == 1)
						@if($data->sku != '')
						<div class="pr_extra"><strong>{{ __('SKU') }}:</strong>  {{ $data->sku }}</div>
						@endif
					@endif
					
					<div class="product_price">
						@if($data->sale_price != '')
							@if($gtext['currency_position'] == 'left')
							<div class="item-price">{{ $gtext['currency_icon'] }}{{ number_format($data->sale_price) }}</div>
							@else
							<div class="item-price">{{ number_format($data->sale_price) }}{{ $gtext['currency_icon'] }}</div>
							@endif
						@endif
						@if($data->old_price != '')
							@if($gtext['currency_position'] == 'left')
							<div class="old-item-price">{{ $gtext['currency_icon'] }}{{ number_format($data->old_price) }}</div>
							@else
							<div class="old-item-price">{{ number_format($data->old_price) }}{{ $gtext['currency_icon'] }}</div>
							@endif
						@endif
					</div>
					@if($data->variation_color != '')
					<div class="pr_widget">
						<label class="widget-title">{{ __('Color') }}<span class="red">*</span></label>
						<ul class="widget-color">
						  @foreach(explode(',', $data->variation_color) as $color)
						  @php $color_array = explode('|', $color); @endphp
							<li id="color_{{ $color_array[0] }}" class="tp_color">
								<a data-id="{{ $color_array[0] }}" class="selectcolor" href="javascript:void(0);" title="{{ $color_array[0] }}"><span style="background:{{ $color_array[1] }};"></span></a>
							</li>
						  @endforeach
						</ul>
					</div>
					@endif

					@if($data->variation_size != '')
					<div class="pr_widget">
						<label class="widget-title">{{ __('Size') }}<span class="red">*</span></label>
						<ul class="widget-size">
						@foreach(explode(',', $data->variation_size) as $size)
							<li id="size_{{ $size }}" class="tp_size">
								<a data-id="{{ $size }}" class="selectsize" href="javascript:void(0);">{{ $size }}</a>
							</li>
						@endforeach
						</ul>
					</div>
					@endif
					
					@if($data->is_stock == 1)
						@if($data->stock_status_id == 1)
						<div class="pr_extra"><strong>{{ __('Availability') }}:</strong><span class="instock">{{ $data->stock_qty }} {{ __('In Stock') }}</span></div>
						@else
						<div class="pr_extra"><strong>{{ __('Availability') }}:</strong><span class="stockout">{{ __('Out Of Stock') }}</span></div>
						@endif
					@endif

					<div class="pr_quantity">
						<label for="quantity">{{ __('Quantity') }}<span class="red">*</span></label>
						<input name="quantity" id="quantity" type="number" min="1" max="{{ $data->is_stock == 1 ? $data->stock_qty : 999 }}" value="1">
					</div>
					<div class="pr_buy_cart">
						<a class="btn theme-btn cart product_addtocart" data-id="{{ $data->id }}" data-stockqty="{{ $data->is_stock == 1 ? $data->stock_qty : 999 }}" href="javascript:void(0);">{{ __('Add To Cart') }}</a>
						<a class="btn theme-btn cart wishlist addtowishlist" data-id="{{ $data->id }}" href="javascript:void(0);"><i class="bi bi-heart-fill"></i></a>
						<a class="btn theme-btn cart product_buy_now" data-id="{{ $data->id }}" data-stockqty="{{ $data->is_stock == 1 ? $data->stock_qty : 999 }}" href="javascript:void(0);">{{ __('Buy Now') }}</a>
					</div>
					<div id="variation_required"><p class="red">{{ __('Please select required field.') }}</p></div>
					<div id="quantity_required"><p class="red">{{ __('Please enter quantity.') }}</p></div>
					<div id="stockqty_required"><p class="red">{{ __('The value must be less than or equal to') }} {{ $data->is_stock == 1 ? $data->stock_qty : '' }}</p></div>
					<div id="stockout_required"><p class="red">{{ __('This product out of stock.') }}</p></div>
					<div class="pr_extra"><strong>{{ __('Category') }}:</strong> <a href="{{ route('frontend.product-category', [$data->cat_id, $data->cat_slug]) }}">{{ $data->cat_name }}</a></div>
				</div>
			</div>
		</div>
		<!-- /Single Product/ -->
		
		<!-- Product Description Review -->
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
				
				@if($errors->any())
					<ul class="errors-list">
					@foreach($errors->all() as $error)
						<li>{{$error}}</li>
					@endforeach
					</ul>
				@endif
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="pr-description-review">
					<div class="desc-review-nav nav">
						<a class="active" href="#des_description" data-bs-toggle="tab">{{ __('Description') }}</a>
						<a href="#des_reviews" data-bs-toggle="tab">{{ __('Reviews') }} ({{ $data->TotalReview }})</a>
					</div>
					<div class="tab-content">
						<div id="des_description" class="tab-pane active">
							<div class="entry">
								{!! $data->description !!}
							</div>
						</div>
						<div id="des_reviews" class="tab-pane">
							<div class="review-content">
								<!-- Review Form-->
								<div class="row">
									<div class="col-lg-6">
										<h4>{{ __('Submit your review') }}</h4>
										<p class="theme-color">Please <a href="{{ route('frontend.login') }}">login</a> to write review!</p>
										<div class="form-product-review">
											<form class="form" method="POST" action="{{ route('frontend.saveReviews') }}">
												@csrf
												
												@if(isset(Auth::user()->name))
												<div class="mb-3">
													<textarea name="comments" placeholder="{{ __('Write comment') }}" class="form-control" rows="3"></textarea>
												</div>
												<div class="mb-3">
													<label for="rating" class="form-label">{{ __('Your rating of this product') }}</label>
													<select id="rating" name="rating" class="form-select form-select-sm">
														<option value="5">5 Star</option>
														<option value="4">4 Star</option>
														<option value="3">3 Star</option>
														<option value="2">2 Star</option>
														<option value="1">1 Star</option>
													</select>
												</div>
												<input name="item_id" type="hidden" value="{{ $data->id }}" />
												<button type="submit" class="btn theme-btn" >{{ __('Submit Review') }}</button>
												@else
												<div class="mb-3">
													<textarea name="comments" placeholder="{{ __('Write comment') }}" class="form-control" rows="3" disabled></textarea>
												</div>
												<a class="btn theme-btn" href="{{ route('frontend.login') }}"><i class="bi bi-box-arrow-in-right"></i> {{ __('Please Login') }}</a>
												@endif
											</form>
										</div>
									</div>
									<div class="col-lg-6"></div>
								</div>
								<!-- /Review Form/-->
								
								<!-- Product Review -->
								@if(count($pro_reviews)>0)
								<div class="row">
									<div class="col-lg-12">
										<div class="review-heading">
											<h4>{{ $data->TotalReview }} {{ __('reviews for') }} - {{ $data->title }}</h4>
										</div>
										<div id="tp_datalist">
											@include('frontend.partials.products-reviews-grid')
										</div>
									</div>
								</div>
								@endif
								<!-- /Product Review/ -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /Product Description Review/ -->
	</div>
</div>
<!-- /Product Details/ -->

<!-- Related Products -->
<div class="section">
	<div class="container">
		<div class="row">
			<div class="section-heading">
				<h3 class="title">{{ __('Related Products') }}</h3>
				<a class="btn theme-btn seeall-btn" href="{{ route('frontend.product-category', [$data->cat_id, $data->cat_slug]) }}">{{ __('See all') }}</a>
			</div>
		</div>
		<div class="row owl-carousel caro-common category-carousel">
			@if(count($related_products)>0)
			@foreach ($related_products as $row)
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
						<a href="{{ route('frontend.product', [$row->id, $row->slug]) }}"><img src="{{ asset('public/media/'.$row->f_thumbnail) }}" alt="{{ $row->title }}" /></a>
					</div>
					<h4 class="item-title"><a href="{{ route('frontend.product', [$row->id, $row->slug]) }}">{{ str_limit($row->title) }}</a></h4>
					<div class="brand-card">
						<div class="brand">
							<span>{{ __('Brand') }} <a href="{{ route('frontend.brand', [$row->brand_id, str_slug($row->brandname)]) }}">{{ str_limit($row->brandname) }}</a></span>
						</div>
						<div class="brand">
							<span>{{ __('Sold By') }} <a href="{{ route('frontend.stores', [$row->seller_id, str_slug($row->shop_url)]) }}">{{ str_limit($row->shop_name) }}</a></span>
						</div>
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
			@foreach ($category_products as $row)
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
						<a href="{{ route('frontend.product', [$row->id, $row->slug]) }}"><img src="{{ asset('public/media/'.$row->f_thumbnail) }}" alt="{{ $row->title }}" /></a>
					</div>
					<h4 class="item-title"><a href="{{ route('frontend.product', [$row->id, $row->slug]) }}">{{ str_limit($row->title) }}</a></h4>
					<div class="brand-card">
						<div class="brand">
							<span>{{ __('Brand') }} <a href="{{ route('frontend.brand', [$row->brand_id, str_slug($row->brandname)]) }}">{{ str_limit($row->brandname) }}</a></span>
						</div>
						<div class="brand">
							<span>{{ __('Sold By') }} <a href="{{ route('frontend.stores', [$row->seller_id, str_slug($row->shop_url)]) }}">{{ str_limit($row->shop_name) }}</a></span>
						</div>
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
			@endif
		</div>
	</div>
</div>
<!-- /Related Products/ -->
@endsection

@push('scripts')
<script type="text/javascript">
	var item_id = "{{ $data->id }}";
	var is_stock = "{{ $data->is_stock }}";
	var is_stock_status = "{{ $data->stock_status_id }}";
	var gcolor = "{{ $data->variation_color }}";
	var gsize = "{{ $data->variation_size }}";
	var is_color = 0;
	var is_size = 0;
	if(gcolor !=''){
		is_color = 1;
	}else{
		is_color = 0;
	}
	if(gsize !=''){
		is_size = 1;
	}else{
		is_size = 0;
	}
</script>
<script src="{{asset('public/frontend/pages/product.js')}}"></script>
@endpush