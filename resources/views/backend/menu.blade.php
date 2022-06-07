@extends('layouts.backend')

@section('title', __('Menu'))

@section('content')
<!-- main Section -->
<div class="main-body">
	<div class="container-fluid">
		@php $vipc = vipc(); @endphp
		@if($vipc['bkey'] == 0) 
		@include('backend.partials.vipc')
		@else
		<div class="row mt-25">
			<div class="col-lg-3">
				<div class="card">
					<div class="card-body">
						<form novalidate="" data-validate="parsley" id="DataEntry_formId">
							<div class="form-group">
								<label for="menu_name">{{ __('Menu Name') }}<span class="red">*</span></label>
								<input type="text" name="menu_name" id="menu_name" class="form-control parsley-validated" data-required="true">
							</div>
							<div class="form-group">
								<label for="menu_position">{{ __('Menu Position') }}<span class="red">*</span></label>
								<select name="menu_position" id="menu_position" class="chosen-select form-control">
									<option value="header">Header Menu</option>
									<option value="footer_left">Footer left</option>
									<option value="footer_right">Footer right</option>
								</select>
							</div>
							<div class="form-group">
								<label for="lan">{{ __('Language') }}<span class="red">*</span></label>
								<select name="lan" id="lan" class="chosen-select form-control">
								@foreach($languagelist as $row)
									<option value="{{ $row->language_code }}">
										{{ $row->language_name }}
									</option>
								@endforeach
								</select>
							</div>
							<div class="form-group">
								<label for="status_id">{{ __('Menu Status') }}<span class="red">*</span></label>
								<select name="status_id" id="status_id" class="chosen-select form-control">
								@foreach($statuslist as $row)
									<option value="{{ $row->id }}">
										{{ $row->status }}
									</option>
								@endforeach
								</select>
							</div>
							<input class="dnone" type="text" name="RecordId" id="RecordId">
							
							<a id="submit-form" href="javascript:void(0);" class="btn blue-btn mr-10">{{ __('Save') }}</a>
						</form>
					</div>
				</div>
			</div>
			<div class="col-lg-9">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-lg-4">
								<div class="form-group bulk-box">
									<select id="bulk-action" class="form-control">
										<option value="">{{ __('Select Action') }}</option>
										<option value="publish">{{ __('Publish') }}</option>
										<option value="draft">{{ __('Draft') }}</option>
										<option value="delete">{{ __('Delete Permanently') }}</option>
									</select>
									<button type="submit" onClick="onBulkAction()" class="btn bulk-btn">{{ __('Apply') }}</button>
								</div>
							</div>
							<div class="col-lg-3"></div>
							<div class="col-lg-5">
								<div class="form-group search-box">
									<input id="search" name="search" type="text" class="form-control" placeholder="{{ __('Search') }}...">
									<button type="submit" onClick="onMenuSearch()" class="btn search-btn">{{ __('Search') }}</button>
								</div>
							</div>
						</div>
						<div id="menu_datalist">
							@include('backend.partials.menu_table')
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
<script type="text/javascript">
var TEXT = [];
	TEXT['Do you really want to edit this record'] = "{{ __('Do you really want to edit this record') }}";
	TEXT['Do you really want to delete this record'] = "{{ __('Do you really want to delete this record') }}";
	TEXT['Do you really want to publish this records'] = "{{ __('Do you really want to publish this records') }}";
	TEXT['Do you really want to draft this records'] = "{{ __('Do you really want to draft this records') }}";
	TEXT['Do you really want to delete this records'] = "{{ __('Do you really want to delete this records') }}";
	TEXT['Please select action'] = "{{ __('Please select action') }}";
	TEXT['Please select record'] = "{{ __('Please select record') }}";
</script>
<script src="{{asset('public/backend/pages/menu.js')}}"></script>
@endpush