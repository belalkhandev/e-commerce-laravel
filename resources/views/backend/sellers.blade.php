@extends('layouts.backend')

@section('title', __('Sellers'))

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
				<div class="card" id="list-panel">
					<div class="card-header">
						<div class="row">
							<div class="col-lg-6">
								<span>{{ __('Sellers') }}</span>
							</div>
							<div class="col-lg-6">
								<div class="float-right">
									<a onClick="onFormPanel()" href="javascript:void(0);" class="btn blue-btn btn-form float-right"><i class="fa fa-plus"></i> {{ __('Add New') }}</a>
								</div>
							</div>
						</div>
					</div>
					
					<!--Data grid-->
					<div class="card-body">
						<div class="row mb-10">
							<div class="col-lg-12">
								<div class="group-button">
									<button id="orderstatus_0" type="button" onclick="onDataViewByStatus(0)" class="btn btn-theme orderstatus active">All ({{ $AllCount }})</button>
									<button id="orderstatus_1" type="button" onclick="onDataViewByStatus(1)" class="btn btn-theme orderstatus">{{ __('Active') }} ({{ $ActiveCount }})</button>
									<button id="orderstatus_2" type="button" onclick="onDataViewByStatus(2)" class="btn btn-theme orderstatus">{{ __('Inactive') }} ({{ $InactiveCount }})</button>
								</div>
								<input type="hidden" id="view_by_status" value="0">
							</div>
						</div>
					
						<div class="row">
							<div class="col-lg-4">
								<div class="form-group bulk-box">
									<select id="bulk-action" class="form-control">
										<option value="">{{ __('Select Action') }}</option>
										<option value="active">{{ __('Active') }}</option>
										<option value="inactive">{{ __('Inactive') }}</option>
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
							@include('backend.partials.sellers_table')
						</div>
					</div>
					<!--/Data grid/-->
				</div>
				
				<div class="dnone" id="form-panel">
					<div class="row">
						<div class="col-md-9">
							<div class="card">
								<div class="card-header">
									<div class="row">
										<div class="col-lg-6">
											<span>{{ __('Sellers') }}</span>
										</div>
										<div class="col-lg-6">
											<div class="float-right">
												<a onClick="onListPanel()" href="javascript:void(0);" class="btn warning-btn btn-list float-right dnone"><i class="fa fa-reply"></i> {{ __('Back to List') }}</a>
											</div>
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
														<label for="shop_name">{{ __('Shop Name') }}<span class="red">*</span></label>
														<input type="text" name="shop_name" id="shop_name" class="form-control parsley-validated" data-required="true">
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label for="shop_url">{{ __('Shop URL') }}<span class="red">*</span></label>
														<input type="text" name="shop_url" id="shop_url" class="form-control parsley-validated" data-required="true">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="phone">{{ __('Shop Phone') }}<span class="red">*</span></label>
														<input type="text" name="phone" id="phone" class="form-control parsley-validated" data-required="true">
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label for="address">{{ __('Address') }}<span class="red">*</span></label>
														<input type="text" name="address" id="address" class="form-control parsley-validated" data-required="true">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="city">{{ __('City') }}<span class="red">*</span></label>
														<input type="text" name="city" id="city" class="form-control parsley-validated" data-required="true">
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label for="state">{{ __('State') }}<span class="red">*</span></label>
														<input type="text" name="state" id="state" class="form-control parsley-validated" data-required="true">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="zip_code">{{ __('Zip Code') }}<span class="red">*</span></label>
														<input type="text" name="zip_code" id="zip_code" class="form-control parsley-validated" data-required="true">
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label for="country_id">{{ __('Country') }}<span class="red">*</span></label>
														<select name="country_id" id="country_id" class="chosen-select form-control">
														@foreach($countrylist as $row)
															<option value="{{ $row->id }}">
																{{ $row->country_name }}
															</option>
														@endforeach
														</select>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="status_id">{{ __('Active/Inactive') }}<span class="red">*</span></label>
														<select name="status_id" id="status_id" class="chosen-select form-control">
														@foreach($statuslist as $row)
															<option value="{{ $row->id }}">
																{{ $row->status }}
															</option>
														@endforeach
														</select>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label for="photo_thumbnail">{{ __('Logo') }}</label>
														<div class="tp-upload-field">
															<input type="text" name="photo" id="photo_thumbnail" class="form-control" readonly>
															<a id="on_thumbnail" href="javascript:void(0);" class="tp-upload-btn"><i class="fa fa-window-restore"></i>{{ __('Browse') }}</a>
														</div>
														<em>Recommended image size width: 200px and height: 200px.</em>
														<div id="remove_photo_thumbnail" class="select-image">
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
														<input type="text" name="bank_name" id="bank_name" class="form-control">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="bank_code">{{ __('Bank Code/IFSC') }}</label>
														<input type="text" name="bank_code" id="bank_code" class="form-control">
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label for="account_number">{{ __('Account Number') }}</label>
														<input type="text" name="account_number" id="account_number" class="form-control">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="account_holder">{{ __('Account Holder Name') }}</label>
														<input type="text" name="account_holder" id="account_holder" class="form-control">
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<label for="paypal_id">{{ __('PayPal ID') }}</label>
														<input type="text" name="paypal_id" id="paypal_id" class="form-control">
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<label for="description">{{ __('Description') }}</label>
														<textarea name="description" id="description" class="form-control" rows="3"></textarea>
													</div>
												</div>
											</div>

											<input type="text" id="seller_id" name="seller_id" class="dnone"/>
											<input type="text" id="bank_information_id" name="bank_information_id" class="dnone"/>
											
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
						<div class="col-md-3">
							<div class="card mb-15">
								<div class="card-body">
									<div class="seller_card">
										<h5><strong>{{ __('Joined At') }}</strong> <span class="float-right" id="created_at"></span></h5>
										<h6><strong>{{ __('Status') }}</strong> <span id="seller_status" class="float-right"></span></h6>
									</div>
								</div>
							</div>
							<div class="status-card bg-grad-5 mb-15">
								<div class="status-text">
									<div class="status-name opacity50">{{ __('Current Balance') }}</div>
									<h2 class="status-count" id="Current_Balance"></h2>
								</div>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 200">
									<path fill="rgba(255,255,255,0.2)" fill-opacity="1" d="M0,32L34.3,58.7C68.6,85,137,139,206,138.7C274.3,139,343,85,411,53.3C480,21,549,11,617,10.7C685.7,11,754,21,823,42.7C891.4,64,960,96,1029,138.7C1097.1,181,1166,235,1234,218.7C1302.9,203,1371,117,1406,74.7L1440,32L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path>
								</svg>
							</div>
							
							<div class="status-card bg-grad-10 mb-15">
								<div class="status-text">
									<div class="status-name opacity50">{{ __('Total Withdraw') }}</div>
									<h2 class="status-count" id="WithdrawalBalance"></h2>
								</div>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 200">
									<path fill="rgba(255,255,255,0.2)" fill-opacity="1" d="M0,32L34.3,58.7C68.6,85,137,139,206,138.7C274.3,139,343,85,411,53.3C480,21,549,11,617,10.7C685.7,11,754,21,823,42.7C891.4,64,960,96,1029,138.7C1097.1,181,1166,235,1234,218.7C1302.9,203,1371,117,1406,74.7L1440,32L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path>
								</svg>
							</div>
							
							<div class="status-card bg-grad-9 mb-15">
								<div class="status-text">
									<div class="status-name opacity50">{{ __('Total Sold') }}</div>
									<h2 class="status-count" id="OrderBalance"></h2>
								</div>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 200">
									<path fill="rgba(255,255,255,0.2)" fill-opacity="1" d="M0,32L34.3,58.7C68.6,85,137,139,206,138.7C274.3,139,343,85,411,53.3C480,21,549,11,617,10.7C685.7,11,754,21,823,42.7C891.4,64,960,96,1029,138.7C1097.1,181,1166,235,1234,218.7C1302.9,203,1371,117,1406,74.7L1440,32L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path>
								</svg>
							</div>
							
							<div class="status-card bg-grad-4 mb-15">
								<div class="status-text">
									<div class="status-name opacity50">{{ __('Total Products') }}</div>
									<h2 class="status-count" id="TotalProducts"></h2>
								</div>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 200">
									<path fill="rgba(255,255,255,0.2)" fill-opacity="1" d="M0,32L34.3,58.7C68.6,85,137,139,206,138.7C274.3,139,343,85,411,53.3C480,21,549,11,617,10.7C685.7,11,754,21,823,42.7C891.4,64,960,96,1029,138.7C1097.1,181,1166,235,1234,218.7C1302.9,203,1371,117,1406,74.7L1440,32L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path>
								</svg>
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

<!--Global Media-->
@include('backend.partials.global_media')
<!--/Global Media/-->

@endsection

@push('scripts')
<!-- css/js -->
<script type="text/javascript">
var media_type = 'Thumbnail';
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
<script src="{{asset('public/backend/pages/sellers.js')}}"></script>
<script src="{{asset('public/backend/pages/global-media.js')}}"></script>
@endpush