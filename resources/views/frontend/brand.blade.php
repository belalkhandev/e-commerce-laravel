@extends('layouts.frontend')

@section('title', $metadata['name'])
@php $gtext = gtext(); @endphp

@section('meta-content')
	<meta name="keywords" content="{{ $metadata['name'] }}" />
	<meta name="description" content="{{ $metadata['name'] }}" />
	<meta property="og:title" content="{{ $metadata['name'] }}" />
	<meta property="og:site_name" content="{{ $gtext['site_name'] }}" />
	<meta property="og:description" content="{{ $metadata['name'] }}" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="{{ url()->current() }}" />
	<meta property="og:image" content="{{ asset('public/media/'.$metadata['thumbnail']) }}" />
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
	<meta name="twitter:title" content="{{ $metadata['name'] }}">
	<meta name="twitter:description" content="{{ $metadata['name'] }}">
	<meta name="twitter:image" content="{{ asset('public/media/'.$metadata['thumbnail']) }}">
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
						<h1>{{ $metadata['name'] }}</h1>
					</div>
				</div>
				<div class="col-lg-6">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
							<li class="breadcrumb-item active" aria-current="page">{{ $metadata['name'] }}</li>
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
				<div class="col-lg-3">
					@include('frontend.partials.sidebar')
				</div>
				<div class="col-lg-9">
					<div class="filter-card">
						<div class="row">
							<div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
								<div class="filter_select">
									<select name="num" id="num" class="form-select form-select-sm">
										<option value="12" selected="">{{ __('Showing') }}</option>
										<option value="45">45</option>
										<option value="60">60</option>
										<option value="90">90</option>
									</select>
								</div>
							</div>
							<div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
								<div class="sort_by_select">
									<select name="sortby" id="sortby" class="form-select form-select-sm">
										<option value="" selected="">{{ __('Default') }}</option>
										<option value="date_asc">Oldest</option>
										<option value="date_desc">Newest</option>
										<option value="name_asc">Name: A-Z</option>
										<option value="name_desc">Name : Z-A</option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div id="tp_datalist">
						@include('frontend.partials.brand-grid')
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /Category Page/ -->
@endsection

@push('scripts')
<script type="text/javascript">
var brand_id = "{{ isset($params) ? $params['brand_id'] : 0 }}";
</script>
<script src="{{asset('public/frontend/pages/brand.js')}}"></script>
@endpush	