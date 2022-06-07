@extends('layouts.backend')

@section('title', __('Variations'))

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
								{{ __('Variations') }}
							</div>
							<div class="col-lg-6">
								<div class="float-right">
									<a href="{{ route('backend.products') }}" class="btn warning-btn"><i class="fa fa-reply"></i> {{ __('Back to List') }}</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body tabs-area p-0">
						@include('backend.partials.product_tabs_nav')
						<div class="tabs-body">
							<!--Data Entry Form-->
							<form novalidate="" data-validate="parsley" id="DataEntry_formId">
								<div class="row">	
									<div class="col-md-12">
										<div class="form-group">
											<label for="variation_size">{{ __('Size') }}</label>
											<select data-placeholder="{{ __('Select Size') }}" name="variation_size[]" id="variation_size" class="chosen-select form-control" multiple>
											@foreach($sizelist as $row)
												<option value="{{ $row->name }}">
													{{ $row->name }}
												</option>
											@endforeach
											</select>
										</div>
									</div>
								</div>
								
								<div class="row">	
									<div class="col-md-12">
										<div class="form-group">
											<label for="variation_color">{{ __('Color') }}</label>
											<select data-placeholder="{{ __('Select color') }}" name="variation_color[]" id="variation_color" class="chosen-select form-control" multiple>
											@foreach($colorlist as $key=>$row)
												<option value="{{ $row->name }}|{{ $row->color }}">
													{{ $row->name }}
												</option>
											@endforeach
											</select>
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

var sizes = "{{ $datalist['variation_size'] }}";
if(sizes !=''){
	var sizesArr = sizes.split(",");
	$("#variation_size").val(sizesArr).trigger("chosen:updated");
}

var colors = "{{ $datalist['variation_color'] }}";
if(colors !=''){
	var colorsArr = colors.split(",");
	$("#variation_color").val(colorsArr).trigger("chosen:updated");
}

</script>
<script src="{{asset('public/backend/pages/variations.js')}}"></script>
@endpush