@extends('layouts.backend')

@section('title', __('MailChimp Settings'))

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
					<div class="card-header">{{ __('MailChimp Settings') }}</div>
					<div class="card-body">
						<!--/Data Entry Form-->
						<form novalidate="" data-validate="parsley" id="mailchimp_formId">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="mailchimp_api_key">{{ __('MailChimp API Key') }}<span class="red">*</span></label>
										<input type="text" name="mailchimp_api_key" id="mailchimp_api_key" class="form-control parsley-validated" data-required="true" value="{{ $datalist['mailchimp_api_key'] }}">
										<small class="form-text text-muted"><a target="_blank" href="https://mailchimp.com/">Creating MailChimp API Key</a></small>
									</div>
								</div>
								<div class="col-md-6"></div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="audience_id">{{ __('Audience ID') }}<span class="red">*</span></label>
										<input type="text" name="audience_id" id="audience_id" class="form-control parsley-validated" data-required="true" value="{{ $datalist['audience_id'] }}">
									</div>
								</div>
								<div class="col-md-6"></div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="tw_checkbox checkbox_group">
										<input id="is_mailchimp" name="is_mailchimp" type="checkbox" {{ $datalist['is_mailchimp'] == 1 ? 'checked' : '' }}>
										<label for="is_mailchimp">{{ __('Enable/Disable') }}</label>
										<span></span>
									</div>
								</div>
							</div>
							<div class="row tabs-footer mt-15">
								<div class="col-lg-12">
									<a id="mailchimp_submit_form" href="javascript:void(0);" class="btn blue-btn mr-10">{{ __('Save') }}</a>
								</div>
							</div>
						</form>
						<!--/Data Entry Form-->
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
<script src="{{asset('public/backend/pages/newsletters.js')}}"></script>
@endpush