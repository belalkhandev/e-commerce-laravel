@extends('layouts.backend')

@section('title', __('Language Keywords'))

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
					<div class="card-header">{{ __('Language Keywords') }}</div>
					<div class="card-body">
						<div class="tabs-head">
							<div class="row">
								<div class="col-md-3">
									<div class="form-group mb-10 filter">
										<select name="language_code" id="language_code" class="chosen-select form-control">
										@foreach($languageslist as $row)
											<option {{ $row->language_default == 1 ? "selected=selected" : '' }} value="{{ $row->language_code }}">
												{{ $row->language_name }}
											</option>
										@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-9">
									<div class="float-right">
										<a onClick="onFormPanel()" href="javascript:void(0);" class="btn blue-btn btn-form float-right"><i class="fa fa-plus"></i> {{ __('Add New') }}</a>
										<a onClick="onListPanel()" href="javascript:void(0);" class="btn warning-btn btn-list float-right dnone"><i class="fa fa-reply"></i> {{ __('Back to List') }}</a>
									</div>
								</div>
							</div>
						</div>
						<!--/Data grid-->
						<div id="list-panel">
							<div class="row">
								<div class="col-lg-7"></div>
								<div class="col-lg-5">
									<div class="form-group search-box">
										<input id="search" name="search" type="text" class="form-control" placeholder="{{ __('Search') }}...">
										<button type="submit" onClick="onSearch()" class="btn search-btn">{{ __('Search') }}</button>
									</div>
								</div>
							</div>
							<div id="tp_datalist">
								@include('backend.partials.languages_keywords_table')
							</div>
						</div><!--/Data grid-->
						
						<!--/Data Entry Form-->
						<div id="form-panel" class="dnone">
							<form novalidate="" data-validate="parsley" id="DataEntry_formId">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="language_key">{{ __('Language Key') }}<span class="red">*</span></label>
											<input type="text" name="language_key" id="language_key" class="form-control parsley-validated" data-required="true">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="language_value">{{ __('Language Name') }}<span class="red">*</span></label>
											<input type="text" name="language_value" id="language_value" class="form-control parsley-validated" data-required="true">
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
							
							<div class="row mt-15">
								<div class="col-md-12">
									<p>Note: <span class="text-danger">Please give write permissions in this folder "resources/lang".</span></p>
								</div>
							</div>
						</div>
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
<script type="text/javascript">
var TEXT = [];
	TEXT['Do you really want to edit this record'] = "{{ __('Do you really want to edit this record') }}";
	TEXT['Do you really want to delete this record'] = "{{ __('Do you really want to delete this record') }}";
</script>
<script src="{{asset('public/backend/pages/languages-keywords.js')}}"></script>
@endpush