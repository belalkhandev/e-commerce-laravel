@extends('layouts.backend')

@section('title', __('Currency'))

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
							<div class="col-lg-6">
								<span>{{ __('Currency') }}</span>
							</div>
							<div class="col-lg-6"></div>
						</div>
					</div>

					<!--Data Entry Form-->
					<div class="card-body">
						<form novalidate="" data-validate="parsley" id="DataEntry_formId">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="currency_name">{{ __('Currency Name') }}<span class="red">*</span></label>
										<input value="{{ $datalist['currency_name'] }}" type="text" name="currency_name" id="currency_name" class="form-control parsley-validated" data-required="true" placeholder="USD">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="currency_icon">{{ __('Currency Icon') }}<span class="red">*</span></label>
										<input value="{{ $datalist['currency_icon'] }}" type="text" name="currency_icon" id="currency_icon" class="form-control parsley-validated" data-required="true" placeholder="$">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="currency_position">{{ __('Currency Position') }}<span class="red">*</span></label>
										<select name="currency_position" id="currency_position" class="chosen-select form-control">
											<option {{ 'left' == $datalist['currency_position'] ? "selected=selected" : '' }} value="left">Left</option>
											<option {{ 'right' == $datalist['currency_position'] ? "selected=selected" : '' }} value="right">Right</option>
										</select>
									</div>
								</div>
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
<script src="{{asset('public/backend/pages/currency.js')}}"></script>
@endpush