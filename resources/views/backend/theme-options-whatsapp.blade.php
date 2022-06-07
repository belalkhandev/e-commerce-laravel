@extends('layouts.backend')

@section('title', __('Whatsapp'))

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
							<div class="col-lg-12">
								{{ __('Whatsapp') }}
							</div>
						</div>
					</div>
					<div class="card-body tabs-area p-0">
						@include('backend.partials.theme_options_tabs_nav')
						<div class="tabs-body">
							<!--Data Entry Form-->
							<form novalidate="" data-validate="parsley" id="DataEntry_formId">
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<label for="whatsapp_id">Whatsapp Phone Number</label>
											<input value="{{ $datalist['whatsapp_id'] }}" type="text" name="whatsapp_id" id="whatsapp_id" class="form-control" placeholder="0123456789">
										</div>
									</div>
									<div class="col-lg-6"></div>
								</div>
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<label for="whatsapp_text">Text</label>
											<input value="{{ $datalist['whatsapp_text'] }}" type="text" name="whatsapp_text" id="whatsapp_text" class="form-control" placeholder="Text..">
										</div>
									</div>
									<div class="col-lg-6"></div>
								</div>
								<div class="row">	
									<div class="col-md-4">
										<div class="form-group">
											<label for="position">Position</label>
											<select name="position" id="position" class="chosen-select form-control">
												<option {{ 'left' == $datalist['position'] ? "selected=selected" : '' }} value="left">Left</option>
												<option {{ 'right' == $datalist['position'] ? "selected=selected" : '' }} value="right">Right</option>
											</select>
										</div>
									</div>
									<div class="col-md-8"></div>
								</div>
								<div class="row">	
									<div class="col-md-4">
										<div class="form-group">
											<label for="is_publish">{{ __('Status') }}</label>
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
<script src="{{asset('public/backend/pages/theme_option_whatsapp.js')}}"></script>
@endpush