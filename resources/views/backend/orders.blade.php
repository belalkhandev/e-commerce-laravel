@extends('layouts.backend')

@section('title', __('Orders'))

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
								<span>{{ __('Orders') }}</span>
							</div>
							<div class="col-lg-6">
								<div class="group-button float-right">
									<button type="button" onClick="onExcelExport()" class="btn btn-theme mb0 btn-padding"><i class="fa fa-download"></i> {{ __('Excel') }}</button>
									<button type="button" onClick="onCSVExport()" class="btn btn-theme mb0 btn-padding"><i class="fa fa-download"></i> {{ __('CSV') }}</button>
								</div>
							</div>
						</div>
					</div>
					<!--Data grid-->
					<div class="card-body">
						<div class="row mb-10">
							<div class="col-lg-8">
								<div class="group-button">
									<button id="orderstatus_0" type="button" onClick="onDataViewByStatus(0)" class="btn btn-theme orderstatus active">{{ __('All') }} (@php echo OrderCount(0); @endphp)</button>
									@foreach($order_status_list as $row)
									<button id="orderstatus_{{ $row->id }}" type="button" onClick="onDataViewByStatus({{ $row->id }})" class="btn btn-theme orderstatus">{{ $row->ostatus_name }} (@php echo OrderCount($row->id); @endphp)</button>
									@endforeach
								</div>
								<input type="hidden" id="view_by_status" value="0"/>
							</div>
							<div class="col-lg-4">
								<div class="filter-form-group pull-right">
									<input name="start_date" id="start_date" type="text" class="form-control" placeholder="yyyy-mm-dd">
									<input name="end_date" id="end_date" type="text" class="form-control" placeholder="yyyy-mm-dd">
									<button type="submit" onClick="onFilterAction()" class="btn btn-theme">{{ __('Filter') }}</button>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-4">
								<div class="form-group bulk-box">
									<select id="order_status_id" class="form-control">
										<option value="">{{ __('Select Action') }}</option>
										@foreach($order_status_list as $row)
										<option value="{{ $row->id }}">{{ $row->ostatus_name }}</option>
										@endforeach
									</select>
									<button type="submit" onClick="onBulkAction()" class="btn bulk-btn">{{ __('Apply') }}</button>
								</div>
							</div>
							<div class="col-lg-3"></div>
							<div class="col-lg-5">
								<div class="form-group search-box">
									<input id="search" name="search" type="text" class="form-control" placeholder="{{ __('Search') }}...">
									<button type="submit" onClick="onSearch()" class="btn search-btn">{{ __('Search') }}</button>
								</div>
							</div>
						</div>
						<div id="tp_datalist">
							@include('backend.partials.orders_table')
						</div>
					</div>
					<!--/Data grid/-->
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
<link rel="stylesheet" href="{{asset('public/backend/bootstrap-datetimepicker/bootstrap-fonticon.css')}}">
<link rel="stylesheet" href="{{asset('public/backend/bootstrap-datetimepicker/bootstrap-datetimepicker.css')}}">
<script src="{{asset('public/backend/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js')}}"></script>
<script type="text/javascript">
var TEXT = [];
	TEXT['Do you really want to delete this record'] = "{{ __('Do you really want to delete this record') }}";
	TEXT['Please select action'] = "{{ __('Please select action') }}";
	TEXT['Please select record'] = "{{ __('Please select record') }}";
$(function () {
	"use strict";
	$("#start_date").datetimepicker({
		format: 'yyyy-mm-dd',
		autoclose: true,
		todayBtn: true,
		minView: 2
	});

	$("#end_date").datetimepicker({
		format: 'yyyy-mm-dd',
		autoclose: true,
		todayBtn: true,
		minView: 2
	});
});
</script>
<script src="{{asset('public/backend/pages/orders.js')}}"></script>
@endpush