@extends('layouts.backend')

@section('title', __('Withdrawals'))

@section('content')
<!-- main Section -->
<div class="main-body">
	<div class="container-fluid">
		@php $vipc = vipc(); @endphp
		@if($vipc['bkey'] == 0) 
		@include('backend.partials.vipc')
		@else
		@php 
		$gtext = gtext(); 
		$gsellersettings = gSellerSettings(); 
		@endphp
		<div class="row mt-25">
			<div class="col-lg-12">
				<div class="card" id="list-panel">
					<div class="card-header">
						<div class="row">
							<div class="col-lg-12">
								<span>{{ __('Withdrawals') }}</span>
							</div>
						</div>
					</div>
					<!--Data grid-->
					<div class="card-body">
						<div class="row">
							<div class="col-md-3">
								<div class="form-group mb-10">
									<select name="f_status_id" id="f_status_id" class="chosen-select form-control">
										<option value="0" selected="selected">{{ __('All Status') }}</option>
										@foreach($statuslist as $row)
											<option value="{{ $row->id }}">
												{{ $row->status }}
											</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-lg-5"></div>
							<div class="col-lg-4">
								<div class="form-group search-box">
									<input id="search" name="search" type="text" class="form-control" placeholder="{{ __('Search') }}...">
									<button type="submit" onClick="onSearch()" class="btn search-btn">{{ __('Search') }}</button>
								</div>
							</div>
						</div>
						<div id="tp_datalist">
							@include('backend.partials.withdrawals_table')
						</div>
					</div>
				</div>
				<!--/Data grid/-->
				
				<div id="form-panel" class="dnone">
					<div class="row">
						<div class="col-lg-7">
							<div class="card mb-15">
								<div class="card-header">
									<div class="row">
										<div class="col-lg-6">
											<span>{{ __('Withdrawals') }}</span>
										</div>
										<div class="col-lg-6">
											<div class="float-right">
												<a onClick="onListPanel()" href="javascript:void(0);" class="btn warning-btn btn-list float-right dnone"><i class="fa fa-reply"></i> {{ __('Back to List') }}</a>
											</div>
										</div>
									</div>
								</div>				
								<!--Data Entry Form-->
								<div class="card-body">
									<form novalidate="" data-validate="parsley" id="DataEntry_formId">
										<div class="row">
											<div class="col-lg-12">
												<div class="form-group">
													<label for="amount">{{ __('Amount') }} ({{ $gtext['currency_icon'] }})<span class="red">*</span></label>
													<input data-range="[1, 999999]"  data-trigger="keyup" type="number" name="amount" id="amount" class="form-control parsley-validated" data-required="true" readonly>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12">
												<div class="form-group">
													<label for="fee_amount">{{ __('Fee') }} ({{ $gtext['currency_icon'] }})<span class="red">*</span></label>
													<input type="number" name="fee_amount" id="fee_amount" class="form-control parsley-validated" value="{{ $gsellersettings['fee_withdrawal'] }}" data-required="true" readonly>
												</div>
											</div>
										</div>
										<div class="row">	
											<div class="col-lg-12">
												<div class="form-group">
													<label for="payment_method">{{ __('Payment Method') }}</label>
													<input type="text" name="payment_method" id="payment_method" class="form-control">
												</div>
											</div>	
										</div>
										<div class="row">
											<div class="col-lg-12">
												<div class="form-group">
													<label for="transaction_id">{{ __('Transaction ID') }}</label>
													<input type="text" name="transaction_id" id="transaction_id" class="form-control">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12">
												<div class="form-group">
													<label for="description">{{ __('Description') }}</label>
													<textarea name="description" id="description" class="form-control" rows="3"></textarea>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-lg-3">
												<div class="form-group">
													<label for="status_id">{{ __('Status') }}<span class="red">*</span></label>
													<select name="status_id" id="status_id" class="chosen-select form-control">
													@foreach($statuslist as $row)
														<option value="{{ $row->id }}">
															{{ $row->status }}
														</option>
													@endforeach
													</select>
												</div>
											</div>
											<div class="col-lg-9"></div>
										</div>
										<input type="text" name="RecordId" id="RecordId" class="dnone">
										<div class="row tabs-footer mt-15">
											<div class="col-lg-12">
												<a id="submit-form" href="javascript:void(0);" class="btn blue-btn">{{ __('Save') }}</a>
											</div>
										</div>
									</form>
								</div>
								<!--/Data Entry Form/-->
							</div>
						</div>
						<div class="col-lg-5">
							<div class="status-card bg-grad-5 mb-15">
								<div class="status-text">
									<div class="status-name opacity50">{{ __('Current Balance') }}</div>
									<h2 class="status-count" id="Current_Balance"></h2>
								</div>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 200">
									<path fill="rgba(255,255,255,0.2)" fill-opacity="1" d="M0,32L34.3,58.7C68.6,85,137,139,206,138.7C274.3,139,343,85,411,53.3C480,21,549,11,617,10.7C685.7,11,754,21,823,42.7C891.4,64,960,96,1029,138.7C1097.1,181,1166,235,1234,218.7C1302.9,203,1371,117,1406,74.7L1440,32L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path>
								</svg>
							</div>
							
							<div class="card mb-15">
								<div class="card-header">
									<span>{{ __('Seller Information') }}</span>
								</div>
								<div class="card-body">
									<div class="seller_card seller_info" id="seller_info"></div>
								</div>
							</div>
							
							<div class="alert alert-success mb-15">
								<div class="seller_card">
									<h4 class="alert-heading">{{ __('Bank Information') }}</h4>
									<div id="bank_info"></div>
								</div>
							</div>
							
							<div class="card mb-15">
								<div class="card-header">
									<a id="on_thumbnail" href="javascript:void(0);" class="btn blue-btn">{{ __('Add Screenshot') }}</a>
								</div>
								<div class="card-body">
									<div class="seller_card">
										<input type="text" name="screenshot" id="screenshot" class="form-control dnone" readonly>
										<ul class="screenshot_list" id="screenshot_list"></ul>
									</div>
								</div>
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
</script>
<link rel="stylesheet" href="{{asset('public/backend/css/magnific-popup.css')}}" />
<script src="{{asset('public/backend/js/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset('public/backend/pages/withdrawals.js')}}"></script>
<script src="{{asset('public/backend/pages/global-media.js')}}"></script>
@endpush