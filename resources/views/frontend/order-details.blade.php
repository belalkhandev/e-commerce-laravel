@extends('layouts.frontend')

@section('title', __('Order Details'))
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
						<h1>{{ __('Order Details') }}</h1>
					</div>
				</div>
				<div class="col-lg-6">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
							<li class="breadcrumb-item active" aria-current="page">{{ __('Order Details') }}</li>
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
								<div class="row mb10">
									<div class="col-lg-6 mb10">
										<h5>{{ __('BILL TO') }}:</h5>
										<p class="mb5"><strong>{{ $mdata->customer_name }}</strong></p>
										<p class="mb5">{{ $mdata->customer_address }}</p>
										<p class="mb5">{{ $mdata->city }}, {{ $mdata->state }}, {{ $mdata->country }}</p>
										<p class="mb5">{{ $mdata->customer_email }}</p>
										<p class="mb5">{{ $mdata->customer_phone }}</p>
									</div>
									<div class="col-lg-6 mb10 order_status">
										<p class="mb5"><strong>{{ __('Order#') }}</strong>: {{ $mdata->order_no }}</p>
										<p class="mb5"><strong>{{ __('Order Date') }}</strong>: {{ date('d-m-Y', strtotime($mdata->created_at)) }}</p>
										<p class="mb5"><strong>{{ __('Payment Method') }}</strong>: {{ $mdata->method_name }}</p>
										<p class="mb5"><strong>{{ __('Payment Status') }}</strong>: <span class="status_btn pstatus_{{ $mdata->payment_status_id }}">{{ $mdata->pstatus_name }}</span></p>
										<p class="mb5"><strong>{{ __('Order Status') }}</strong>: <span class="status_btn ostatus_{{ $mdata->order_status_id }}">{{ $mdata->ostatus_name }}</span></p>
									</div>
								</div>
								<div class="row mt15">
									<div class="col-lg-12">
										<div class="table-responsive">
											<table class="table">
												<thead>
													<tr>
														<th>{{ __('Image') }}</th>
														<th>{{ __('Product') }}</th>
														<th>{{ __('Variation') }}</th>
														<th class="text-center">{{ __('Price') }}</th>
														<th class="text-center">{{ __('Quantity') }}</th>
														<th class="text-center">{{ __('Total') }}</th>
													</tr>
												</thead>
												<tbody>
													@foreach($datalist as $row)
													@php
														if($gtext['currency_position'] == 'left'){
															$price = $gtext['currency_icon'].number_format($row->price);
															$total_price = $gtext['currency_icon'].number_format($row->total_price);
														}else{
															$price = number_format($row->price).$gtext['currency_icon'];
															$total_price = number_format($row->total_price).$gtext['currency_icon'];
														}

														if($row->variation_color == '0'){
															$color = '&nbsp;';
														}else{
															$color = 'Color: '.$row->variation_color.'&nbsp;';
														}

														if($row->variation_size == '0'){
															$size = '&nbsp;';
														}else{
															$size = 'Size: '.$row->variation_size;
														}
													@endphp
													<tr>
														<td class="pro-image-w">
															<div class="pro-image">
																<a href="{{ route('frontend.product', [$row->id, str_slug($row->title)]) }}">
																	<img src="{{ asset('public/media/'.$row->f_thumbnail) }}" alt="{{ $row->title }}" />
																</a>
															</div>
														</td>
														<td class="pro-name-w">
															<span class="pro-name"><a href="{{ route('frontend.product', [$row->id, str_slug($row->title)]) }}">{{ $row->title }}</a></span>
														</td>
														<td class="text-left">@php echo $color.$size; @endphp</td>
														<td class="text-center">{{ $price }}</td>
														<td class="text-center">{{ $row->quantity }}</td>
														<td class="text-center">{{ $total_price }}</td>
													</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-lg-4 mt10"></div>
									<div class="col-lg-3"></div>
									<div class="col-lg-5 mt10">
										<div class="carttotals-card">
											<div class="carttotals-body">
												<table class="table">
													<tbody>
														@php	
															$total_amount_shipping_fee = $mdata->total_amount+$mdata->shipping_fee+$mdata->tax;

															if($gtext['currency_position'] == 'left'){
																$shipping_fee = $gtext['currency_icon'].number_format($mdata->shipping_fee, 2);
																$tax = $gtext['currency_icon'].number_format($mdata->tax, 2);
																$subtotal = $gtext['currency_icon'].number_format($mdata->total_amount, 2);
																$total_amount = $gtext['currency_icon'].number_format($total_amount_shipping_fee, 2);
																
															}else{
																$shipping_fee = number_format($mdata->shipping_fee, 2).$gtext['currency_icon'];
																$tax = number_format($mdata->tax, 2).$gtext['currency_icon'];
																$subtotal = number_format($mdata->total_amount, 2).$gtext['currency_icon'];
																$total_amount = number_format($total_amount_shipping_fee, 2).$gtext['currency_icon'];
															}
														@endphp
														
														<tr><td><span class="title">{{ __('Shipping Fee') }}<br>({{ $mdata->shipping_title }})</span><span class="price">{{ $shipping_fee }}</span></td></tr>
														<tr><td><span class="title">{{ __('Tax') }}</span><span class="price">{{ $tax }}</span></td></tr>
														<tr><td><span class="title">{{ __('Subtotal') }}</span><span class="price">{{ $subtotal }}</span></td></tr>
														<tr><td><span class="total">{{ __('Total') }}</span><span class="total-price">{{ $total_amount }}</span></td></tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
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
<script type="text/javascript">
	var my_dashbord_href = location.href;
	var my_dashbord_elem = '.sidebar-nav li a[href="' + my_dashbord_href + '"]';
	$('ul.sidebar-nav li').parent().removeClass('active');
	$('ul.sidebar-nav li a').parent().removeClass('active');
	$(my_dashbord_elem).addClass('active');
</script>
@endpush	