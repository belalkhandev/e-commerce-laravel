@extends('layouts.frontend')

@section('title', __('Orders'))
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
						<h1>{{ __('Orders') }}</h1>
					</div>
				</div>
				<div class="col-lg-6">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
							<li class="breadcrumb-item active" aria-current="page">{{ __('Orders') }}</li>
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
								<div class="table-responsive">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th class="text-left" style="width:15%">{{ __('Order#') }}</th>
												<th class="text-left" style="width:10%">{{ __('Order Date') }}</th>
												<th class="text-center" style="width:10%">{{ __('Amount') }}</th>
												<th class="text-center" style="width:5%">{{ __('Qty') }}</th>
												<th class="text-center" style="width:16%">{{ __('Payment Method') }}</th>
												<th class="text-center" style="width:16%">{{ __('Payment Status') }}</th>
												<th class="text-center" style="width:20%">{{ __('Order Status') }}</th>
												<th class="text-center" style="width:8%">{{ __('Action') }}</th>
											</tr>
										</thead>
										<tbody>
											@if (count($datalist)>0)
											@foreach($datalist as $row)
											@php 
												$total_amount = $row->total_amount+$row->shipping_fee+$row->tax;
											@endphp
											<tr>
												<td class="text-left"><a href="{{ route('frontend.order-details', [$row->id, $row->order_no]) }}">{{ $row->order_no }}</a></td>
												<td class="text-left">{{ date('d-m-Y', strtotime($row->created_at)) }}</td>

												@if($gtext['currency_position'] == 'left')
												<td class="text-center">{{ $gtext['currency_icon'] }}{{ number_format($total_amount, 2) }}</td>
												@else
												<td class="text-center">{{ number_format($total_amount, 2) }}{{ $gtext['currency_icon'] }}</td>
												@endif
												
												<td class="text-center">{{ $row->total_qty }}</td>
												<td class="text-center">{{ $row->method_name }}</td>
												<td class="text-center"><span class="status_btn pstatus_{{ $row->payment_status_id }}">{{ $row->pstatus_name }}</span></td>
												<td class="text-center"><span class="status_btn ostatus_{{ $row->order_status_id }}">{{ $row->ostatus_name }}</span></td>
												
												<td class="text-center">
													<a title="{{ __('Invoice') }}" class="mr10" href="{{ route('frontend.order-invoice', [$row->id, $row->order_no]) }}"><i class="bi bi-cloud-arrow-down"></i></a>
													<a title="{{ __('View') }}" href="{{ route('frontend.order-details', [$row->id, $row->order_no]) }}"><i class="bi bi-eye"></i></a>
												</td>
											</tr>
											@endforeach
											@else
											<tr>
												<td class="text-center" colspan="8">{{ __('No data available') }}</td>
											</tr>
											@endif
										</tbody>
									</table>
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