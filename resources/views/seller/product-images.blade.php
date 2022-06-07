@extends('layouts.backend')

@section('title', __('Multiple Images'))

@section('content')
<!-- main Section -->
<div class="main-body">
	<div class="container-fluid">
		@php $vipc = vipc(); @endphp
		@if($vipc['bkey'] == 0) 
		@include('seller.partials.vipc')
		@else
		<div class="row mt-25">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header">
						<div class="row">
							<div class="col-lg-6">
								{{ __('Multiple Images') }}
							</div>
							<div class="col-lg-6">
								<div class="float-right">
									<a href="{{ route('seller.products') }}" class="btn warning-btn"><i class="fa fa-reply"></i> {{ __('Back to List') }}</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body tabs-area p-0">
						@include('seller.partials.product_tabs_nav')
						<div class="tabs-body">
							<!--Data Entry Form-->
							<form novalidate="" data-validate="parsley" id="DataEntry_formId">
								<div class="row">
									<div class="col-md-8">
										<div class="form-group">
											<label for="pro_thumbnail">{{ __('Image') }}<span class="red">*</span></label>
											<div class="file_up">
												<input type="text" name="thumbnail" id="pro_thumbnail" value="{{ $datalist['thumbnail'] }}" class="form-control parsley-validated" data-required="true" readonly>
												<div class="file_browse_box">
													<input type="file" name="load_image" id="load_image" class="file_browse">
													<label for="load_image" class="file_browse_icon"><i class="fa fa-window-restore"></i>{{ __('Browse') }}</label>
												</div>
											</div>
											<small class="form-text text-muted">Recommended image size width: 600px and height: 600px.</small>
											<input type="text" name="large_image" id="pro_large_image" class="dnone">
										</div>
									</div>
									<div class="col-md-4"></div>
								</div>
								<div class="row mt-15">
									<div class="col-lg-12">
										<a id="submit-form" href="javascript:void(0);" class="btn blue-btn">{{ __('Save') }}</a>
									</div>
								</div>
							</form>
							<!--/Data Entry Form/-->
							
							<!--Image list-->
							<div id="tp_datalist">
								@include('seller.partials.product_images_list')
							</div>
							<!--/Image list/-->
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
var media_type = 'Product_Thumbnail';
var product_id = "{{ $datalist['id'] }}";
var TEXT = [];
	TEXT['Sorry only you can upload jpg, png and gif file type'] = "{{ __('Sorry only you can upload jpg, png and gif file type') }}";
	TEXT['Do you really want to delete this record'] = "{{ __('Do you really want to delete this record') }}";
</script>
<script src="{{asset('public/backend/pages/product_images_seller.js')}}"></script>
@endpush