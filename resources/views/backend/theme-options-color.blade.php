@extends('layouts.backend')

@section('title', __('Color'))

@section('content')
<!-- main Section -->
<div class="main-body">
	<div class="container-fluid">
		@php $vipc = vipc(); @endphp
		@if($vipc['bkey'] == 0) 
		@include('backend.partials.vipc')
		@else
		<div class="row mt-25">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header">
						<div class="row">
							<div class="col-lg-12">
								{{ __('Color') }}
							</div>
						</div>
					</div>
					<div class="card-body tabs-area p-0">
						@include('backend.partials.theme_options_tabs_nav')
						<div class="tabs-body">
							<!--Data Entry Form-->
							<form novalidate="" data-validate="parsley" id="DataEntry_formId">
								<div class="row">
									<div class="col-lg-8">
										<div class="form-group">
											<label>{{ __('Theme color') }}<span class="red">*</span></label>
											<div id="theme_color_picker" class="input-group tw-picker">
												<input name="theme_color" id="theme_color" type="text" value="{{ $datalist['theme_color'] == '' ? '#38a677' : $datalist['theme_color'] }}" class="form-control"/>
												<span class="input-group-addon"><i></i></span>
											</div>
										</div>
										<div class="form-group">
											<label>{{ __('Menu Background Color') }}<span class="red">*</span></label>
											<div id="menu_background_color_picker" class="input-group tw-picker">
												<input name="menu_background_color" id="menu_background_color" type="text" value="{{ $datalist['menu_background_color'] == '' ? '#38a677' : $datalist['menu_background_color'] }}" class="form-control"/>
												<span class="input-group-addon"><i></i></span>
											</div>
										</div>
										<div class="form-group">
											<label>{{ __('Backend Theme color') }}<span class="red">*</span></label>
											<div id="backend_theme_color_picker" class="input-group tw-picker">
												<input name="backend_theme_color" id="backend_theme_color" type="text" value="{{ $datalist['backend_theme_color'] == '' ? '#38a677' : $datalist['backend_theme_color'] }}" class="form-control"/>
												<span class="input-group-addon"><i></i></span>
											</div>
										</div>
									</div>
									<div class="col-lg-4"></div>
								</div>
								
								<div class="row tabs-footer mt-15">
									<div class="col-lg-12">
										<a id="submit-form" href="javascript:void(0);" class="btn blue-btn">{{ __('Save') }}</a>
									</div>
								</div>
							</form>
							<!--/Data Entry Form/-->
						</div>
					</div>
				</div>
			</div>
		</div>
		@endif
	</div>
</div>
<!-- /main Section -->
@endsection

@push('scripts')
<!-- css/js -->
<link rel="stylesheet" href="{{asset('public/backend/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}">
<script src="{{asset('public/backend/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{asset('public/backend/pages/theme_option_color.js')}}"></script>
@endpush