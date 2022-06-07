@extends('layouts.backend')

@section('title', __('Settings'))

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
							<div class="col-lg-12">
								<span>{{ __('Settings') }}</span>
							</div>
						</div>
					</div>
					<!--/Data Entry Form-->
					<div class="card-body">
						<a onClick="onDetailsBankInfo(1)" href="javascript:void(0);" id="details_bank_info_1" class="btn custom-btn font-bold mr-10 details_bank_info active">{{ __('Details') }}</a>
						<a onClick="onDetailsBankInfo(2)" href="javascript:void(0);" id="details_bank_info_2" class="btn custom-btn font-bold details_bank_info">{{ __('Bank Information') }}</a>
						
						<!--Details-->
						<div class="mt-15" id="details">
							<form novalidate="" data-validate="parsley" id="DataEntry_formId">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="shop_name">{{ __('Shop Name') }}<span class="red">*</span></label>
											<input type="text" name="shop_name" id="shop_name" value="{{ $seller_data['shop_name'] }}" class="form-control parsley-validated" data-required="true">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="shop_url">{{ __('Shop URL') }}<span class="red">*</span></label>
											<input type="text" name="shop_url" id="shop_url" value="{{ $seller_data['shop_url'] }}" class="form-control parsley-validated" data-required="true">
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="phone">{{ __('Shop Phone') }}<span class="red">*</span></label>
											<input type="text" name="phone" id="phone" value="{{ $seller_data['phone'] }}" class="form-control parsley-validated" data-required="true">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="address">{{ __('Address') }}<span class="red">*</span></label>
											<input type="text" name="address" id="address" value="{{ $seller_data['address'] }}" class="form-control parsley-validated" data-required="true">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="city">{{ __('City') }}<span class="red">*</span></label>
											<input type="text" name="city" id="city" value="{{ $seller_data['city'] }}" class="form-control parsley-validated" data-required="true">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="state">{{ __('State') }}<span class="red">*</span></label>
											<input type="text" name="state" id="state" value="{{ $seller_data['state'] }}" class="form-control parsley-validated" data-required="true">
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="zip_code">{{ __('Zip Code') }}<span class="red">*</span></label>
											<input type="text" name="zip_code" id="zip_code" value="{{ $seller_data['zip_code'] }}" class="form-control parsley-validated" data-required="true">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="country_id">{{ __('Country') }}<span class="red">*</span></label>
											<select name="country_id" id="country_id" class="chosen-select form-control">
											@foreach($countrylist as $row)
												<option {{ $row->id == $seller_data['country_id'] ? "selected=selected" : '' }} value="{{ $row->id }}">
													{{ $row->country_name }}
												</option>
											@endforeach
											</select>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="f_thumbnail_thumbnail"><span class="red">*</span> {{ __('Logo') }}</label>
											<div class="file_up">
												<input type="text" name="photo" id="f_thumbnail_thumbnail" value="{{ $seller_data['photo'] }}" class="form-control parsley-validated" data-required="true" readonly>
												<div class="file_browse_box">
													<input type="file" name="load_image" id="load_image" class="file_browse">
													<label for="load_image" class="file_browse_icon"><i class="fa fa-window-restore"></i>{{ __('Browse') }}</label>
												</div>
											</div>
											<small class="form-text text-muted">Recommended image size width: 200px and height: 200px.</small>
											<div id="remove_f_thumbnail" class="select-image dnone">
												<div class="inner-image" id="view_thumbnail_image"></div>
											</div>
										</div>
									</div>
									<div class="col-md-6"></div>
								</div>
								
								<input type="text" id="RecordId" name="RecordId" class="dnone" value="{{ $seller_data['id'] }}"/>
								
								<div class="row tabs-footer mt-15">
									<div class="col-lg-12">
										<a id="submit-form" href="javascript:void(0);" class="btn blue-btn mr-10">{{ __('Save') }}</a>
									</div>
								</div>
							</form>
						</div>
						<!--/Details/-->
						
						<!--Bank Information-->
						<div class="mt-15 dnone" id="bank_information">
							<form novalidate="" data-validate="parsley" id="bankInformation_formId">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="bank_name">{{ __('Bank Name') }}</label>
											<input type="text" name="bank_name" id="bank_name" value="{{ $bank_information['bank_name'] }}" class="form-control">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="bank_code">{{ __('Bank Code/IFSC') }}</label>
											<input type="text" name="bank_code" id="bank_code" value="{{ $bank_information['bank_code'] }}" class="form-control">
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="account_number">{{ __('Account Number') }}</label>
											<input type="text" name="account_number" id="account_number" value="{{ $bank_information['account_number'] }}" class="form-control">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="account_holder">{{ __('Account Holder Name') }}</label>
											<input type="text" name="account_holder" id="account_holder" value="{{ $bank_information['account_holder'] }}" class="form-control">
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="paypal_id">{{ __('PayPal ID') }}</label>
											<input type="text" name="paypal_id" id="paypal_id" value="{{ $bank_information['paypal_id'] }}" class="form-control">
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="description">{{ __('Description') }}</label>
											<textarea name="description" id="description" class="form-control" rows="3">{{ $bank_information['description'] }}</textarea>
										</div>
									</div>
								</div>

								<input type="text" id="seller_id" name="seller_id" value="{{ $seller_data['id'] }}" class="dnone"/>
								<input type="text" id="bank_information_id" name="bank_information_id" value="{{ $bank_information['id'] }}" class="dnone"/>
								
								<div class="row tabs-footer mt-15">
									<div class="col-lg-12">
										<a id="bank_information_submit_form" href="javascript:void(0);" class="btn blue-btn mr-10">{{ __('Save') }}</a>
									</div>
								</div>
							</form>
						</div>
						<!--/Bank Information/-->
					</div>
					<!--/Data Entry Form-->
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
var media_type = 'Thumbnail';

var f_thumbnail = "{{ $seller_data['photo'] }}";
if(f_thumbnail == ''){
	$("#remove_f_thumbnail").hide();
	$("#f_thumbnail_thumbnail").html('');
}
if(f_thumbnail != ''){
	$("#remove_f_thumbnail").show();
	$("#view_thumbnail_image").html('<img src="'+public_path+'/media/'+f_thumbnail+'">');
}

var TEXT = [];
	TEXT['Do you really want to edit this record'] = "{{ __('Do you really want to edit this record') }}";
	TEXT['Do you really want to delete this record'] = "{{ __('Do you really want to delete this record') }}";
	TEXT['Do you really want to active this records'] = "{{ __('Do you really want to active this records') }}";
	TEXT['Do you really want to inactive this records'] = "{{ __('Do you really want to inactive this records') }}";
	TEXT['Do you really want to delete this records'] = "{{ __('Do you really want to delete this records') }}";
	TEXT['Please select action'] = "{{ __('Please select action') }}";
	TEXT['Please select record'] = "{{ __('Please select record') }}";
	TEXT['Active'] = "{{ __('Active') }}";
	TEXT['Inactive'] = "{{ __('Inactive') }}";
</script>
<script src="{{asset('public/backend/pages/settings-seller.js')}}"></script>
@endpush