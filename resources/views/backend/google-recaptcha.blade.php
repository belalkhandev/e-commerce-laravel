@extends('layouts.backend')

@section('title', __('Google reCAPTCHA'))

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
							<!--Google reCAPTCHA Setting-->
							<div id="GoogleRecaptchaSetting">
								<form novalidate="" data-validate="parsley" id="GoogleRecaptcha_formId">
									<div class="row">
										<div class="col-lg-8">
											<div class="tw_checkbox checkbox_group">
												<input id="recaptcha" name="recaptcha" type="checkbox" {{ $datalist['is_recaptcha'] == 1 ? 'checked' : '' }}>
												<label for="recaptcha">{{ __('Enable/Disable') }}</label>
												<span></span>
											</div>
											<div class="form-group">
												<label for="sitekey">{{ __('Site Key') }}<span class="red">*</span></label>
												<input type="text" name="sitekey" id="sitekey" class="form-control parsley-validated" data-required="true" value="{{ $datalist['sitekey'] }}">
											</div>
											<div class="form-group">
												<label for="secretkey">{{ __('Secret Key') }}<span class="red">*</span></label>
												<input type="text" name="secretkey" id="secretkey" class="form-control parsley-validated" data-required="true" value="{{ $datalist['secretkey'] }}">
												<small class="form-text text-muted"><a target="_blank" href="https://www.google.com/recaptcha/admin/create">Create Google reCAPTCHA v2</a></small>
											</div>
										</div>
										<div class="col-lg-4"></div>
									</div>
									<div class="row tabs-footer mt-15">
										<div class="col-lg-12">
											<a id="recaptcha-submit-form" href="javascript:void(0);" class="btn blue-btn">{{ __('Save') }}</a>
										</div>
									</div>
								</form>
							</div>
							<!--/Google reCAPTCHA Setting-->
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
<script src="{{asset('public/backend/pages/google-recaptcha.js')}}"></script>
@endpush