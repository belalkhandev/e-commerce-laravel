@extends('layouts.backend')

@section('title', __('Languages'))

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
					<div class="card-header">{{ __('Languages') }}</div>
					<div class="card-body">
						<div class="tabs-head">
							<div class="float-right">
								<a onClick="onFormPanel()" href="javascript:void(0);" class="btn blue-btn btn-form float-right"><i class="fa fa-plus"></i> {{ __('Add New') }}</a>
								<a onClick="onListPanel()" href="javascript:void(0);" class="btn warning-btn btn-list float-right dnone"><i class="fa fa-reply"></i> {{ __('Back to List') }}</a>
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
								@include('backend.partials.languages_table')
							</div>
						</div><!--/Data grid-->
						
						<!--/Data Entry Form-->
						<div id="form-panel" class="dnone">
							<form novalidate="" data-validate="parsley" id="DataEntry_formId">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="language_code">{{ __('Language Code') }}<span class="red">*</span></label>
											<input type="text" name="language_code" id="language_code" class="form-control parsley-validated" data-required="true">
											<small class="form-text text-muted">Example: af, bn, en, fr, pt (<a target="_blank" href="https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes">List of Langauges name and Codes</a>) </small>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="language_name">{{ __('Language Name') }}<span class="red">*</span></label>
											<input type="text" name="language_name" id="language_name" class="form-control parsley-validated" data-required="true">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="tw_checkbox checkbox_group">
											<input id="language_default" name="language_default" type="checkbox">
											<label for="language_default">{{ __('Default Language') }}</label>
											<span></span>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="tw_checkbox checkbox_group">
											<input id="is_rtl" name="is_rtl" type="checkbox">
											<label for="is_rtl">{{ __('RTL') }}</label>
											<span></span>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="tw_checkbox checkbox_group">
											<input id="status" name="status" type="checkbox">
											<label for="status">{{ __('Active/Inactive') }}</label>
											<span></span>
										</div>
									</div>
								</div>
								
								<input id="old_language_code" name="old_language_code" type="text" class="dnone"/>
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
	TEXT['Delete'] = "{{ __('Delete') }}";
	TEXT['Edit'] = "{{ __('Edit') }}";
	TEXT['Enable'] = "{{ __('Enable') }}";
	TEXT['Disable'] = "{{ __('Disable') }}";
</script>
<script src="{{asset('public/backend/pages/languages.js')}}"></script>
@endpush