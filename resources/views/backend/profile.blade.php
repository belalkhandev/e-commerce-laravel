@extends('layouts.backend')

@section('title', __('Profile'))

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
					<div class="card-header">{{ __('Profile') }}</div>
					<div class="card-body">
						<!--/Data Entry Form-->
						<form novalidate="" data-validate="parsley" id="DataEntry_formId">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="name">{{ __('Name') }}<span class="red">*</span></label>
										<input type="text" name="name" id="name" class="form-control parsley-validated" data-required="true">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="email">{{ __('Email Address') }}<span class="red">*</span></label>
										<input type="email" name="email" id="email" class="form-control parsley-validated" data-required="true">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group relative">
										<label for="password">{{ __('Password') }}<span class="red">*</span></label>
										<span toggle="#password" class="fa fa-eye field-icon toggle-password"></span>
										<input type="password" name="password" id="password" class="form-control parsley-validated" data-required="true">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="phone">{{ __('Phone') }}</label>
										<input type="text" name="phone" id="phone" class="form-control">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="address">{{ __('Address') }}</label>
										<textarea name="address" id="address" class="form-control" rows="3"></textarea>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="photo_thumbnail">{{ __('Profile Photo') }}</label>
										<div class="tp-upload-field">
											<input type="text" name="photo" id="photo_thumbnail" class="form-control" readonly>
											<a id="on_thumbnail" href="javascript:void(0);" class="tp-upload-btn"><i class="fa fa-window-restore"></i>{{ __('Browse') }}</a>
										</div>
										<em>Recommended image size width: 200px and height: 200px.</em>
										<div id="remove_photo_thumbnail" class="select-image dnone">
											<div class="inner-image" id="view_photo_thumbnail"></div>
											<a onClick="onMediaImageRemove('photo_thumbnail')" class="media-image-remove" href="javascript:void(0);"><i class="fa fa-remove"></i></a>
										</div>
									</div>
								</div>
								<div class="col-md-6"></div>
							</div>
							
							<input type="text" id="RecordId" name="RecordId" class="dnone"/>
							
							<div class="row tabs-footer mt-15">
								<div class="col-lg-12">
									<a id="submit-form" href="javascript:void(0);" class="btn blue-btn">{{ __('Save') }}</a>
								</div>
							</div>
						</form>
						<!--/Data Entry Form-->
					</div>
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
<script type="text/javascript">
var media_type = 'Thumbnail';
</script>
<script src="{{asset('public/backend/pages/profile.js')}}"></script>
<script src="{{asset('public/backend/pages/global-media.js')}}"></script>
@endpush