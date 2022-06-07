@extends('layouts.backend')

@section('title', __('Subscribers'))

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
					<div class="card-header">{{ __('Subscribers') }} </div>
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
								<div class="col-lg-7"><p class="theme-color please_wait"></p></div>
								<div class="col-lg-5">
									<div class="form-group search-box">
										<input id="search" name="search" type="text" class="form-control" placeholder="{{ __('Search') }}...">
										<button type="submit" onClick="onSearch()" class="btn search-btn">{{ __('Search') }}</button>
									</div>
								</div>
							</div>
							<div id="tp_datalist">
								@include('backend.partials.subscribers_table')
							</div>
						</div><!--/Data grid-->
						
						<!--/Data Entry Form-->
						<div id="form-panel" class="dnone">
							<form novalidate="" data-validate="parsley" id="subscriber_formId">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="email_address">{{ __('Email Address') }}<span class="red">*</span></label>
											<input type="email" name="email_address" id="email_address" class="form-control parsley-validated" data-required="true">
										</div>
									</div>
									<div class="col-md-6"></div>
								</div>
								<div class="row">
									<div class="col-lg-3 mb-10">
										<div class="form-group">
											<label for="status">{{ __('Status') }}<span class="red">*</span></label>
											<select name="status" id="status" class="chosen-select form-control">
												<option value="subscribed">Subscribed</option>
												<option value="unsubscribed">Unsubscribed</option>
											</select>
										</div>
									</div>
									<div class="col-lg-9"></div>
								</div>
								<input type="text" name="RecordId" id="RecordId" class="dnone">

								<div class="row tabs-footer mt-15">
									<div class="col-lg-12">
										<a id="subscriber_submit_form" href="javascript:void(0);" class="btn blue-btn mr-10 subscriber_btn">{{ __('Save') }}</a>
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
<script src="{{asset('public/backend/pages/newsletters.js')}}"></script>
@endpush