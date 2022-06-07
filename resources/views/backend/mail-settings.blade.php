@extends('layouts.backend')

@section('title', __('Mail Settings'))

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
							<!--Mail Setting-->
							<div id="GoogleRecaptchaSetting">
								<form novalidate="" data-validate="parsley" id="submit_formId">
									<div class="row">
										<div class="col-lg-8">
											<div class="tw_checkbox checkbox_group">
												<input id="ismail" name="ismail" type="checkbox" {{ $datalist['ismail'] == 1 ? 'checked' : '' }}>
												<label for="ismail">{{ __('Enable/Disable') }}</label>
												<span></span>
											</div>
											<div class="form-group">
												<label for="from_name">{{ __('From Name') }}<span class="red">*</span></label>
												<input type="text" name="from_name" id="from_name" class="form-control" value="{{ $datalist['from_name'] }}">
											</div>
											<div class="form-group">
												<label for="from_mail">{{ __('From Mail Address') }}<span class="red">*</span></label>
												<input type="text" name="from_mail" id="from_mail" class="form-control" value="{{ $datalist['from_mail'] }}">
												<small class="form-text text-muted">The mail address must be a webmail address. e.g. <strong>admin@companyname.com</strong></small>
											</div>
											<div class="form-group">
												<label for="to_name">{{ __('To Name') }}<span class="red">*</span></label>
												<input type="text" name="to_name" id="to_name" class="form-control" value="{{ $datalist['to_name'] }}">
											</div>
											<div class="form-group">
												<label for="to_mail">{{ __('To Mail Address') }}<span class="red">*</span></label>
												<input type="text" name="to_mail" id="to_mail" class="form-control" value="{{ $datalist['to_mail'] }}">
											</div>
											<div class="form-group">
												<label for="mailer">{{ __('Mailer') }}<span class="red">*</span></label>
												<select name="mailer" id="mailer" class="chosen-select form-control">
													<option value="mail" {{ $datalist['mailer'] == 'mail' ? 'selected="selected"' : '' }}>PHP Mail</option>
													<option value="smtp" {{ $datalist['mailer'] == 'smtp' ? 'selected="selected"' : '' }}>SMTP</option>
												</select>
											</div>

											<div id="is_smtp" {{ $datalist['mailer'] == 'smtp' ? '' : 'class=dnone' }}>
												<div class="form-group">
													<label for="smtp_host">{{ __('SMTP Host') }}<span class="red">*</span></label>
													<input type="text" name="smtp_host" id="smtp_host" class="form-control" value="{{ $datalist['smtp_host'] }}">
												</div>
												<div class="form-group">
													<label for="smtp_port">{{ __('SMTP Port') }}<span class="red">*</span></label>
													<input type="number" name="smtp_port" id="smtp_port" class="form-control" value="{{ $datalist['smtp_port'] }}" placeholder="587">
												</div>
												
												<div class="form-group">
													<label for="smtp_security">{{ __('SMTP Security') }}<span class="red">*</span></label>
													<select name="smtp_security" id="smtp_security" class="chosen-select form-control">
														<option value="tls" {{ $datalist['smtp_security'] == 'tls' ? 'selected="selected"' : '' }}>TLS</option>
														<option value="ssl" {{ $datalist['smtp_security'] == 'ssl' ? 'selected="selected"' : '' }}>SSL</option>
													</select>
												</div>
												<div class="form-group">
													<label for="smtp_username">{{ __('SMTP Username') }}<span class="red">*</span></label>
													<input type="text" name="smtp_username" id="smtp_username" class="form-control" value="{{ $datalist['smtp_username'] }}">
												</div>
												<div class="form-group">
													<label for="smtp_password">{{ __('SMTP Password') }}<span class="red">*</span></label>
													<input type="password" name="smtp_password" id="smtp_password" class="form-control" value="{{ $datalist['smtp_password'] }}">
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
							</div>
							<!--/Mail Setting-->
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
<script src="{{asset('public/backend/pages/mail-setting.js')}}"></script>
@endpush