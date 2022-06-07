@extends('layouts.backend')

@section('title', __('Social Media'))

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
								{{ __('Social Media') }}
							</div>
							<div class="col-lg-6">
								<div class="float-right">
									<a onClick="onFormPanel()" href="javascript:void(0);" class="btn blue-btn btn-form float-right"><i class="fa fa-plus"></i> {{ __('Add New') }}</a>
									<a onClick="onListPanel()" href="javascript:void(0);" class="btn warning-btn btn-list float-right dnone"><i class="fa fa-reply"></i> {{ __('Back to List') }}</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body tabs-area p-0">
						@include('backend.partials.theme_options_tabs_nav')
						<div class="tabs-body">
							<!--Data grid-->
							<div id="list-panel">
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
											<button type="submit" onClick="onSearch()" class="btn search-btn">{{ __('Search') }}</button>
										</div>
									</div>
								</div>
								<div id="tp_datalist">
									@include('backend.partials.social_media_table')
								</div>
							</div>
							<!--/Data grid/-->
							<!--Data Entry Form-->
							<div id="form-panel" class="dnone">
								<form novalidate="" data-validate="parsley" id="DataEntry_formId">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="title">{{ __('Title') }}<span class="red">*</span></label>
												<input type="text" name="title" id="title" class="form-control parsley-validated" data-required="true">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="url">{{ __('URL') }}<span class="red">*</span></label>
												<input type="text" name="url" id="url" class="form-control parsley-validated" data-required="true">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="social_icon">{{ __('Social Icon') }} <span class="red">*</span></label>
												<input type="text" name="social_icon" id="social_icon" class="form-control parsley-validated" data-required="true" placeholder="bi bi-facebook">
											</div>
											<em>Choose bootstrap icon <a href="https://icons.getbootstrap.com/" target="_blank">https://icons.getbootstrap.com/</a></em>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="target">{{ __('Target Window') }}<span class="red">*</span></label>
												<select name="target" id="target" class="chosen-select form-control">
													<option value="_self">Self</option>
													<option value="_blank">Blank</option>
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="is_publish">{{ __('Status') }}<span class="red">*</span></label>
												<select name="is_publish" id="is_publish" class="chosen-select form-control">
												@foreach($statuslist as $row)
													<option value="{{ $row->id }}">
														{{ $row->status }}
													</option>
												@endforeach
												</select>
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
			</div>
		</div>
		@endif
	</div>
</div>
<!-- /main Section -->

@endsection

@push('scripts')
<!-- css/js -->
<link rel="stylesheet" href="{{asset('public/backend/css/bootstrap-icons.css')}}">
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
<script src="{{asset('public/backend/pages/social_media.js')}}"></script>
@endpush