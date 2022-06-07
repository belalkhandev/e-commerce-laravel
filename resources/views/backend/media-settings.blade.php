@extends('layouts.backend')

@section('title', __('Media Setting'))

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
					<div class="card-header">{{ __('Settings') }}</div>
					<div class="card-body tabs-area p-0">
						@include('backend.partials.settings_tabs_nav')
						<div class="tabs-body">
							<div class="row">
								<div class="col-lg-12">
									<div class="float-right">
										<a onClick="onListPanel()" href="javascript:void(0);" class="btn warning-btn btn-list float-right dnone"><i class="fa fa-reply"></i> {{ __('Back to List') }}</a>
									</div>
								</div>
							</div>
							<div class="row mt-15">
								<div class="col-lg-12">
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
										<div class="row">
											<div class="col-lg-12">
												<div id="media_settings_datalist">
													@include('backend.partials.media_settings_table')
												</div>
											</div>
										</div>
									</div><!--/Data grid-->
								
									<!--/Data Entry Form-->
									<div id="form-panel" class="dnone">
										<form novalidate="" data-validate="parsley" id="DataEntry_formId">
											<div class="row">
												<div class="col-md-12">
													<h4><strong id="media_type"></strong></h4>
													<p>The sizes listed below determine the maximum dimensions in pixels to use when adding an image to the Media.</p>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label for="media_width">{{ __('Width') }}<span class="red">*</span></label>
														<input type="number" name="media_width" id="media_width" class="form-control parsley-validated" data-required="true">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="media_height">{{ __('Height') }}<span class="red">*</span></label>
														<input type="number" name="media_height" id="media_height" class="form-control parsley-validated" data-required="true">
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
									<!--/Data Entry Form-->
								</div>
							</div>
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
<script type="text/javascript">
var TEXT = [];
	TEXT['Do you really want to edit this record'] = "{{ __('Do you really want to edit this record') }}";
</script>
<script src="{{asset('public/backend/pages/media-setting.js')}}"></script>
@endpush