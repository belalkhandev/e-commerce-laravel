@extends('layouts.backend')

@section('title', __('Trending'))

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
								<span>{{ __('Trending') }}</span>
							</div>
							<div class="col-lg-6"></div>
						</div>
					</div>
					<!--Data Entry Form-->
					<div class="card-body">
						<form novalidate="" data-validate="parsley" id="DataEntry_formId">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="title">{{ __('Title') }}<span class="red">*</span></label>
										<input value="{{ $datalist['title'] }}" type="text" name="title" id="trending_title" class="form-control parsley-validated" data-required="true">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="short_desc">{{ __('Short Description') }}</label>
										<textarea name="short_desc" id="short_desc" class="form-control" rows="2">{{ $datalist['short_desc'] }}</textarea>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="url">{{ __('URL') }}<span class="red">*</span></label>
										<input value="{{ $datalist['url'] }}" type="text" name="url" id="url" class="form-control parsley-validated" data-required="true">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="trending_image">{{ __('Image') }}<span class="red">*</span></label>
										<div class="tp-upload-field">
											<input value="{{ $datalist['image'] }}" type="text" name="image" id="trending_image" class="form-control" readonly>
											<a onClick="onGlobalMediaModalView()" href="javascript:void(0);" class="tp-upload-btn"><i class="fa fa-window-restore"></i>{{ __('Browse') }}</a>
										</div>
										<div id="remove_trending_image" class="select-image">
											<div class="inner-image" id="view_trending_image"></div>
											<a onClick="onMediaImageRemove('trending_image')" class="media-image-remove" href="javascript:void(0);"><i class="fa fa-remove"></i></a>
										</div>
									</div>
								</div>
								<div class="col-md-6"></div>
							</div>
							
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="is_publish">{{ __('Status') }}<span class="red">*</span></label>
										<select name="is_publish" id="is_publish" class="chosen-select form-control">
										@foreach($statuslist as $row)
											<option {{ $row->id == $datalist['is_publish'] ? "selected=selected" : '' }} value="{{ $row->id }}">
												{{ $row->status }}
											</option>
										@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-8"></div>
							</div>
							
							<div class="row tabs-footer mt-15">
								<div class="col-lg-12">
									<a id="submit-form" href="javascript:void(0);" class="btn blue-btn mr-10">{{ __('Save') }}</a>
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

<!--Global Media-->
@include('backend.partials.global_media')
<!--/Global Media/-->

@endsection

@push('scripts')
<!-- css/js -->
<script type="text/javascript">
var media_type = 'Thumbnail';

var image = "{{ $datalist['image'] }}";
if(image == ''){
	$("#remove_trending_image").hide();
	$("#trending_image").html('');
}
if(image != ''){
	$("#remove_trending_image").show();
	$("#view_trending_image").html('<img src="'+public_path+'/media/'+image+'">');
}
</script>
<script src="{{asset('public/backend/pages/trending.js')}}"></script>
<script src="{{asset('public/backend/pages/global-media.js')}}"></script>
@endpush