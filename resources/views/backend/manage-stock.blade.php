@extends('layouts.backend')

@section('title', __('Manage Stock'))

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
								<span>{{ __('Manage Stock') }}</span>
							</div>
							<div class="col-lg-6">
								<div class="float-right">
									<a onClick="onListPanel()" href="javascript:void(0);" class="btn warning-btn btn-list float-right dnone"><i class="fa fa-reply"></i> {{ __('Back to List') }}</a>
								</div>
							</div>
						</div>
					</div>
					<!--Data grid-->
					<div id="list-panel" class="card-body">
						<div class="row mb-10">
							<div class="col-md-3">
								<div class="form-group mb-10">
									<select name="language_code" id="language_code" class="chosen-select form-control">
										<option value="0" selected="selected">{{ __('All Language') }}</option>
										@foreach($languageslist as $row)
											<option value="{{ $row->language_code }}">
												{{ $row->language_name }}
											</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-9"></div>
						</div>
						<div class="row">
							<div class="col-lg-7">
								<div class="group-button">
									<button type="button" onClick="onDataViewByStatus(2)" id="viewstatus_2" class="btn btn-theme viewstatus active">{{ __('All') }} ({{ $AllCount }})</button>
									<button type="button" onClick="onDataViewByStatus(1)" id="viewstatus_1" class="btn btn-theme viewstatus">{{ __('In Stock') }} ({{ $InStockCount }})</button>
									<button type="button" onClick="onDataViewByStatus(0)" id="viewstatus_0" class="btn btn-theme viewstatus">{{ __('Out Of Stock') }} ({{ $OutOfStockCount }})</button>
								</div>
								<input type="hidden" id="view_by_status" value="2"/>
							</div>
							<div class="col-lg-5">
								<div class="form-group search-box">
									<input id="search" name="search" type="text" class="form-control" placeholder="{{ __('Search') }}...">
									<button type="submit" onClick="onSearch()" class="btn search-btn">{{ __('Search') }}</button>
								</div>
							</div>
						</div>
						<div id="tp_datalist">
							@include('backend.partials.manage_stock_table')
						</div>
					</div>
					<!--/Data grid/-->
					
					<!--Data Entry Form-->
					<div id="form-panel" class="card-body dnone">
						<!--Data Entry Form-->
						<form novalidate="" data-validate="parsley" id="DataEntry_formId">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="is_stock">{{ __('Manage Stock') }}</label>
										<select name="is_stock" id="is_stock" class="chosen-select form-control">
											<option value="1">{{ __('YES') }}</option>
											<option value="0">{{ __('NO') }}</option>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="stock_status_id">{{ __('Stock Status') }}</label>
										<select name="stock_status_id" id="stock_status_id" class="chosen-select form-control">
											<option value="1">{{ __('In Stock') }}</option>
											<option value="0">{{ __('Out Of Stock') }}</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6">
									<div class="form-group">
										<label for="sku">{{ __('SKU') }}</label>
										<input name="sku" id="sku" type="text" class="form-control">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="stock_qty">{{ __('Stock Quantity') }}</label>
										<input name="stock_qty" id="stock_qty" type="number" class="form-control">
									</div>
								</div>
							</div>
							
							<input type="text" name="RecordId" id="RecordId" class="dnone">
							<div class="row tabs-footer mt-15">
								<div class="col-lg-12">
									<a id="submit-form" href="javascript:void(0);" class="btn blue-btn mr-10">{{ __('Save') }}</a>
									<a onClick="onListPanel()" href="javascript:void(0);" class="btn danger-btn">{{ __('Cancel') }}</a>
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
<script type="text/javascript">
var TEXT = [];
	TEXT['Do you really want to edit this record'] = "{{ __('Do you really want to edit this record') }}";
</script>
<script src="{{asset('public/backend/pages/manage-stock.js')}}"></script>
@endpush