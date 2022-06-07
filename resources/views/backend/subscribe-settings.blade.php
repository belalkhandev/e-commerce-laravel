@extends('layouts.backend')

@section('title', __('Subscribe Settings'))

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
					<div class="card-header">{{ __('Subscribe Settings') }}</div>
					<div class="card-body">
						<!--/Data Entry Form-->
						<form novalidate="" data-validate="parsley" id="subscribe_popup_formId">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="subscribe_popup_desc">{{ __('Description') }}<span class="red">*</span></label>
										<textarea name="subscribe_popup_desc" id="subscribe_popup_desc" class="form-control" rows="3">{{ $datalist['subscribe_popup_desc'] }}</textarea>
									</div>
								</div>
								<div class="col-md-6"></div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<label>{{ __('Subscribe Popup') }}</label>
									<div class="tw_checkbox checkbox_group">
										<input id="is_subscribe_popup" name="is_subscribe_popup" type="checkbox" {{ $datalist['is_subscribe_popup'] == 1 ? 'checked' : '' }}>
										<label for="is_subscribe_popup">{{ __('Enable/Disable') }}</label>
										<span></span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<label>{{ __('Footer Subscribe Box') }}</label>
									<div class="tw_checkbox checkbox_group">
										<input id="is_subscribe_footer" name="is_subscribe_footer" type="checkbox" {{ $datalist['is_subscribe_footer'] == 1 ? 'checked' : '' }}>
										<label for="is_subscribe_footer">{{ __('Enable/Disable') }}</label>
										<span></span>
									</div>
								</div>
							</div>
							<div class="row tabs-footer mt-15">
								<div class="col-lg-12">
									<a id="subscribe_popup_submit_form" href="javascript:void(0);" class="btn blue-btn mr-10">{{ __('Save') }}</a>
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