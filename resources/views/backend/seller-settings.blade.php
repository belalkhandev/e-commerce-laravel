@extends('layouts.backend')

@section('title', __('Settings'))

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
								<span>{{ __('Settings') }}</span>
							</div>
						</div>
					</div>
					<!--Data Entry Form-->
					<div class="card-body">
						<form novalidate="" data-validate="parsley" id="DataEntry_formId">

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="fee_withdrawal">{{ __('Fee withdrawal (Fixed amount)') }}<span class="red">*</span></label>
										<input type="number" value="{{ $datalist['fee_withdrawal'] }}" name="fee_withdrawal" id="fee_withdrawal" class="form-control parsley-validated" data-required="true">
									</div>
								</div>
								<div class="col-md-6"></div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>{{ __('Product auto publish') }}<span class="red">*</span></label>
									<div class="tw_checkbox checkbox_group">
										<input id="product_auto_publish" name="product_auto_publish" type="checkbox" {{ $datalist['product_auto_publish'] == 1 ? 'checked' : '' }}>
										<label for="product_auto_publish">{{ __('YES') }}/{{ __('NO') }}</label>
										<span></span>
									</div>
								</div>
								<div class="col-md-6"></div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>{{ __('Seller auto active') }}<span class="red">*</span></label>
									<div class="tw_checkbox checkbox_group">
										<input id="seller_auto_active" name="seller_auto_active" type="checkbox" {{ $datalist['seller_auto_active'] == 1 ? 'checked' : '' }}>
										<label for="seller_auto_active">{{ __('YES') }}/{{ __('NO') }}</label>
										<span></span>
									</div>
								</div>
								<div class="col-md-6"></div>
							</div>
							<div class="row tabs-footer mt-15">
								<div class="col-lg-12">
									<a id="submit-form" href="javascript:void(0);" class="btn blue-btn mr-10">{{ __('Save') }}</a>
								</div>
							</div>
						</form>
					</div>
					<!--/Data Entry Form/-->
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
<script src="{{asset('public/backend/pages/seller-settings.js')}}"></script>
@endpush