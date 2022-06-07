@extends('layouts.backend')

@section('title', __('Inventory'))

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
								{{ __('Inventory') }}
							</div>
							<div class="col-lg-6">
								<div class="float-right">
									<a href="{{ route('backend.products') }}" class="btn warning-btn"><i class="fa fa-reply"></i> {{ __('Back to List') }}</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body tabs-area p-0">
						@include('backend.partials.product_tabs_nav')
						<div class="tabs-body">
							<!--Data Entry Form-->
							<form novalidate="" data-validate="parsley" id="DataEntry_formId">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="is_stock">{{ __('Manage Stock') }}</label>
											<select name="is_stock" id="is_stock" class="chosen-select form-control">
												<option {{ 1 == $datalist['is_stock'] ? "selected=selected" : '' }} value="1">{{ __('YES') }}</option>
												<option {{ 0 == $datalist['is_stock'] ? "selected=selected" : '' }} value="0">{{ __('NO') }}</option>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="stock_status_id">{{ __('Stock Status') }}</label>
											<select name="stock_status_id" id="stock_status_id" class="chosen-select form-control">
												<option {{ 1 == $datalist['stock_status_id'] ? "selected=selected" : '' }} value="1">{{ __('In Stock') }}</option>
												<option {{ 0 == $datalist['stock_status_id'] ? "selected=selected" : '' }} value="0">{{ __('Out Of Stock') }}</option>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<label for="sku">{{ __('SKU') }}</label>
											<input value="{{ $datalist['sku'] }}" name="sku" id="sku" type="text" class="form-control">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="stock_qty">{{ __('Stock Quantity') }}</label>
											<input value="{{ $datalist['stock_qty'] }}" name="stock_qty" id="stock_qty" type="number" class="form-control">
										</div>
									</div>
								</div>
								
								<input value="{{ $datalist['id'] }}" type="text" name="RecordId" id="RecordId" class="dnone">
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
<script src="{{asset('public/backend/pages/inventory.js')}}"></script>
@endpush