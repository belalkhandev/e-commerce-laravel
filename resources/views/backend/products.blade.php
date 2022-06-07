@extends('layouts.backend')

@section('title', __('Products'))

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
								<span>{{ __('Products') }}</span>
							</div>
							<div class="col-lg-6">
								<div class="float-right">
									<a onClick="onFormPanel()" href="javascript:void(0);" class="btn blue-btn btn-form float-right"><i class="fa fa-plus"></i> {{ __('Add New') }}</a>
									<a onClick="onListPanel()" href="javascript:void(0);" class="btn warning-btn btn-list float-right dnone"><i class="fa fa-reply"></i> {{ __('Back to List') }}</a>
								</div>
							</div>
						</div>
					</div>
					<!--Data grid-->
					<div id="list-panel" class="card-body">
						<div class="row mb-10">
							<div class="col-lg-9">
								<div class="group-button">
									<button type="button" onClick="onDataViewByStatus(0)" id="viewstatus_0" class="btn btn-theme viewstatus active">{{ __('All') }} ({{ $AllCount }})</button>
									<button type="button" onClick="onDataViewByStatus(1)" id="viewstatus_1" class="btn btn-theme viewstatus">{{ __('Published') }} ({{ $PublishedCount }})</button>
									<button type="button" onClick="onDataViewByStatus(2)" id="viewstatus_2" class="btn btn-theme viewstatus">{{ __('Draft') }} ({{ $DraftCount }})</button>
								</div>
								<input type="hidden" id="view_by_status" value="0"/>
							</div>
							<div class="col-md-3"></div>
						</div>
						
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
							<div class="col-md-3">
								<div class="form-group mb-10">
									<select name="category_id" id="category_id" class="chosen-select form-control">
										<option value="0" selected="selected">{{ __('All Category') }}</option>
										@foreach($categorylist as $row)
											<option value="{{ $row->id }}">
												{{ $row->name }}
											</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group mb-10">
									<select name="brand_id" id="brand_id" class="chosen-select form-control">
										<option value="all" selected="selected">{{ __('All Brand') }}</option>
										<option value="0">No Brand</option>
										@foreach($brandlist as $row)
											<option value="{{ $row->id }}">
												{{ $row->name }}
											</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group mb-10">
									<select name="collection_id" id="collection_id" class="chosen-select form-control">
										<option value="all" selected="selected">{{ __('All Collection') }}</option>
										<option value="0">No Collection</option>
										@foreach($collectionlist as $row)
											<option value="{{ $row->id }}">
												{{ $row->name }}
											</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group mb-10">
									<select name="store_id" id="store_id" class="chosen-select form-control">
										<option value="0" selected="selected">{{ __('All Store') }}</option>
										@foreach($storeList as $row)
											<option value="{{ $row->id }}">
												{{ $row->shop_name }}
											</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-9"></div>
						</div>
						
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
							@include('backend.partials.products_table')
						</div>
					</div>
					<!--/Data grid/-->
					
					<!--Data Entry Form-->
					<div id="form-panel" class="card-body dnone">
						<form novalidate="" data-validate="parsley" id="DataEntry_formId">
							<div class="row">
								<div class="col-lg-12">
									<div class="form-group">
										<label for="title">{{ __('Product Name') }}<span class="red">*</span></label>
										<input type="text" name="title" id="title" class="form-control parsley-validated" data-required="true">
									</div>
								</div>
							</div>
							<div class="row">	
								<div class="col-md-12">
									<div class="form-group">
										<label for="slug">{{ __('Slug') }}<span class="red">*</span></label>
										<input type="text" name="slug" id="slug" class="form-control parsley-validated" data-required="true">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="lan">{{ __('Language') }}<span class="red">*</span></label>
										<select name="lan" id="lan" class="chosen-select form-control">
										@foreach($languageslist as $row)
											<option value="{{ $row->language_code }}">
												{{ $row->language_name }}
											</option>
										@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="categoryid">{{ __('Category') }}<span class="red">*</span></label>
										<select name="categoryid" id="categoryid" class="chosen-select form-control">
											@foreach($categorylist as $row)
												<option value="{{ $row->id }}">
													{{ $row->name }}
												</option>
											@endforeach
										</select>
									</div>
								</div>	
								<div class="col-md-3">
									<div class="form-group">
										<label for="brandid">{{ __('Brand') }}<span class="red">*</span></label>
										<select name="brandid" id="brandid" class="chosen-select form-control">
											<option value="0">No Brand</option>
											@foreach($brandlist as $row)
												<option value="{{ $row->id }}">
													{{ $row->name }}
												</option>
											@endforeach
										</select>
									</div>
								</div>	
								<div class="col-md-3">
									<div class="form-group">
										<label for="storeid">{{ __('Store') }}<span class="red">*</span></label>
										<select name="storeid" id="storeid" class="chosen-select form-control">
										@foreach($storeList as $row)
											<option value="{{ $row->id }}">
												{{ $row->shop_name }}
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
	TEXT['All Category'] = "{{ __('All Category') }}";
	TEXT['All Brand'] = "{{ __('All Brand') }}";
</script>
<script src="{{asset('public/backend/pages/products.js')}}"></script>
@endpush