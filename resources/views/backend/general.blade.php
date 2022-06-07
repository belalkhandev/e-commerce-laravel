@extends('layouts.backend')

@section('title', __('General'))

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
					<div class="card-header">{{ __('Settings') }}</div>
					<div class="card-body tabs-area p-0">
						@include('backend.partials.settings_tabs_nav')
						<div class="tabs-body">
							<!--General Setting-->
							<div id="GlobalSetting">
								<form novalidate="" data-validate="parsley" id="DataEntry_formId">
									<div class="row">
										<div class="col-lg-8">
											<div class="divider_heading">{{ __('Invoice Information') }}</div>
											<div class="form-group">
												<label for="site_name">{{ __('Site Name') }}<span class="red">*</span></label>
												<input type="text" name="site_name" id="site_name" class="form-control parsley-validated" data-required="true" value="{{ $datalist['site_name'] }}">
											</div>
											<div class="form-group">
												<label for="site_title">{{ __('Site Title') }}<span class="red">*</span></label>
												<input type="text" name="site_title" id="site_title" class="form-control parsley-validated" data-required="true" value="{{ $datalist['site_title'] }}">
											</div>
											<div class="form-group">
												<label for="company">{{ __('Company') }}<span class="red">*</span></label>
												<input type="text" name="company" id="company" class="form-control parsley-validated" data-required="true" value="{{ $datalist['company'] }}">
											</div>
											<div class="form-group">
												<label for="email">{{ __('Email') }}<span class="red">*</span></label>
												<input type="text" name="email" id="email" class="form-control parsley-validated" data-required="true" value="{{ $datalist['email'] }}">
											</div>
											<div class="form-group">
												<label for="phone">{{ __('Phone') }}<span class="red">*</span></label>
												<input type="text" name="phone" id="phone" class="form-control parsley-validated" data-required="true" value="{{ $datalist['phone'] }}">
											</div>
											<div class="form-group">
												<label for="address">{{ __('Address') }}<span class="red">*</span></label>
												<textarea name="address" id="address" class="form-control parsley-validated" data-required="true" rows="2">{{ $datalist['address'] }}</textarea>
											</div>
											<div class="form-group">
												<label for="timezone">{{ __('Time Zone') }}<span class="red">*</span></label>
												<select name="timezone" id="timezone" class="chosen-select form-control parsley-validated" data-required="true">
												@foreach($timezonelist as $row)
													<option {{ $row->timezone == $datalist['timezone'] ? "selected=selected" : '' }} value="{{ $row->timezone }}">
														{{ $row->timezone_name }}
													</option>
												@endforeach
												</select>
											</div>
										</div>
										<div class="col-lg-4"></div>
									</div>
									<div class="row tabs-footer mt-15">
										<div class="col-lg-12">
											<a id="global-setting-form" href="javascript:void(0);" class="btn blue-btn">{{ __('Save') }}</a>
										</div>
									</div>
								</form>
							</div>
							<!--/General Setting-->
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
<script src="{{asset('public/backend/pages/general.js')}}"></script>
@endpush