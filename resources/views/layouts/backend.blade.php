<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	@php $gtext = gtext(); @endphp
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title') | {{ $gtext['site_title'] }}</title>
	<!-- favicon -->
	<link rel="shortcut icon" href="{{ $gtext['favicon'] ? asset('public/media/'.$gtext['favicon']) : asset('public/backend/images/favicon.ico') }}" type="image/x-icon">
	<link rel="icon" href="{{ $gtext['favicon'] ? asset('public/media/'.$gtext['favicon']) : asset('public/backend/images/favicon.ico') }}" type="image/x-icon">
    <!-- CSS -->
	<style type="text/css">
	:root {
	  --backend-theme-color: {{ $gtext['backend_theme_color'] }};
	}
	</style>
    <link rel="stylesheet" href="{{asset('public/backend/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/backend/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/backend/css/chosen/bootstrap-chosen.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/backend/css/jquery.gritter.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/backend/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('public/backend/css/responsive.css')}}">
	@stack('style')
  </head>
  <body>
	<div id="wrapper" class="d-flex relative">
		<!-- Sidebar -->
		@include('backend.partials.sidebar')
		<!-- /Sidebar/ -->
		<!-- Page Content -->
		<div id="page-content-wrapper">
			<!--Top Navbar-->
			@include('backend.partials.topnav')
			<!--/Top Navbar/-->
			<!--Main Body-->
			@yield('content')	
			<!--/Main Body/-->
		</div><!-- /Page Content/ -->
	</div><!--/wrapper-->
    <!-- JS -->
	<script src="{{asset('public/backend/js/jquery-3.6.0.min.js')}}"></script>
	<script src="{{asset('public/backend/js/popper.min.js')}}"></script>
	<script src="{{asset('public/backend/js/bootstrap.min.js')}}"></script>
	<script src="{{asset('public/backend/js/jquery-ui.min.js')}}"></script>
	<script src="{{asset('public/backend/js/jquery.nicescroll.min.js')}}"></script>
	<script src="{{asset('public/backend/js/parsley.min.js')}}"></script>
	<script src="{{asset('public/backend/js/chosen.jquery.min.js')}}"></script>
	<script src="{{asset('public/backend/js/jquery.popupoverlay.min.js')}}"></script>
	<script src="{{asset('public/backend/js/jquery.gritter.min.js')}}"></script>
	<script type="text/javascript">
	var base_url = "{{ url('/') }}";
	var public_path = "{{ asset('public') }}";
	var userid = "{{ Auth::user()->id }}";
	</script>
	<script src="{{asset('public/backend/js/script.js')}}"></script>
	<div class="custom-popup light width-100 dnone" id="lightCustomModal">
		<div class="padding-md">
			<h4 class="m-top-none">{{ __('This is alert message') }}</h4>
		</div>
		<div class="text-center">
			<a href="javascript:void(0);" class="btn blue-btn lightCustomModal_close mr-10" onClick="onConfirm()">{{ __('Confirm') }}</a>
			<a href="javascript:void(0);" class="btn danger-btn lightCustomModal_close">{{ __('Cancel') }}</a>
		</div>
	</div>
	<a href="#lightCustomModal" class="btn btn-warning btn-small lightCustomModal_open dnone">{{ __('Edit') }}</a>
	@stack('scripts')
  </body>
</html>