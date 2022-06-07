@extends('layouts.backend')

@section('title', __('SEO'))

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
								{{ __('SEO') }}
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
									<div class="col-lg-12">
										<div class="form-group">
											<label for="og_title">{{ __('SEO Title') }}</label>
											<input value="{{ $datalist['og_title'] }}" type="text" name="og_title" id="og_title" class="form-control">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label for="og_keywords">{{ __('SEO Keywords') }}</label>
											<input value="{{ $datalist['og_keywords'] }}" type="text" name="og_keywords" id="og_keywords" class="form-control">
										</div>
									</div>
								</div>
								<div class="row">	
									<div class="col-md-12">
										<div class="form-group">
											<label for="og_description">{{ __('SEO Description') }}</label>
											<textarea name="og_description" id="og_description" class="form-control" rows="2">{{ $datalist['og_description'] }}</textarea>
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="og_image">{{ __('Open Graph Image') }}</label>
											<div class="file_up">
												<input type="text" name="og_image" id="og_image" value="{{ $datalist['og_image'] }}" class="form-control parsley-validated" data-required="true" readonly>
												<div class="file_browse_box">
													<input type="file" name="load_image" id="load_image" class="file_browse">
													<label for="load_image" class="file_browse_icon"><i class="fa fa-window-restore"></i>{{ __('Browse') }}</label>
												</div>
											</div>
											<small class="form-text text-muted">e.g. Facebook share image. Recommended image size width: 600px and height: 315px.</small>
											<div id="remove_og_image" class="select-image dnone">
												<div class="inner-image" id="view_og_image"></div>
											</div>
										</div>
									</div>
								</div>

								<input value="{{ $datalist['id'] }}" type="text" name="RecordId" id="RecordId" class="dnone">
								<div class="row tabs-footer mt-15">
									<div class="col-lg-12">
										<a id="submit-form" href="javascript:void(0);" class="btn blue-btn">{{ __('Save') }}</a>
									</div>
								</div>
							</form>
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
<script type="text/javascript">
var media_type = 'Product_Thumbnail';

var og_image = "{{ $datalist['og_image'] }}";
if(og_image == ''){
	$("#remove_og_image").hide();
	$("#og_image").html('');
}
if(og_image != ''){
	$("#remove_og_image").show();
	$("#view_og_image").html('<img src="'+public_path+'/media/'+og_image+'">');
}

var TEXT = [];
	TEXT['Sorry only you can upload jpg, png and gif file type'] = "{{ __('Sorry only you can upload jpg, png and gif file type') }}";

</script>
<script src="{{asset('public/backend/pages/product_seo_seller.js')}}"></script>
@endpush