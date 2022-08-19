@extends('layouts.frontend')

@section('title', __('Checkout'))
@php
$gtext = gtext();
$gtax = getTax();
$tax_rate = $gtax['percentage'];
config(['cart.tax' => $tax_rate]);
@endphp

@section('meta-content')
	<meta name="keywords" content="{{ $gtext['og_keywords'] }}" />
	<meta name="description" content="{{ $gtext['og_description'] }}" />
	<meta property="og:title" content="{{ $gtext['og_title'] }}" />
	<meta property="og:site_name" content="{{ $gtext['site_name'] }}" />
	<meta property="og:description" content="{{ $gtext['og_description'] }}" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="{{ url()->current() }}" />
	<meta property="og:image" content="{{ asset('public/media/'.$gtext['og_image']) }}" />
	<meta property="og:image:width" content="600" />
	<meta property="og:image:height" content="315" />
	@if($gtext['fb_publish'] == 1)
	<meta name="fb:app_id" property="fb:app_id" content="{{ $gtext['fb_app_id'] }}" />
	@endif
	<meta name="twitter:card" content="summary_large_image">
	@if($gtext['twitter_publish'] == 1)
	<meta name="twitter:site" content="{{ $gtext['twitter_id'] }}">
	<meta name="twitter:creator" content="{{ $gtext['twitter_id'] }}">
	@endif
	<meta name="twitter:url" content="{{ url()->current() }}">
	<meta name="twitter:title" content="{{ $gtext['og_title'] }}">
	<meta name="twitter:description" content="{{ $gtext['og_description'] }}">
	<meta name="twitter:image" content="{{ asset('public/media/'.$gtext['og_image']) }}">
@endsection

@section('header')
@include('frontend.partials.inner-header')
@endsection

@section('content')
	<!-- Page Breadcrumb -->
	<div class="breadcrumb-section">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6">
					<div class="page-title">
						<h1>{{ __('Checkout') }}</h1>
					</div>
				</div>
				<div class="col-lg-6">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
							<li class="breadcrumb-item active" aria-current="page">{{ __('Checkout') }}</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
	<!-- /Page Breadcrumb/ -->
	<!-- Checkout -->
	<div class="section bg-white my_card">
		<div class="container">
			<form novalidate="" data-validate="parsley" id="checkout_formid">
				@csrf
				<div class="row">
					<div class="col-lg-7">
						<h5>{{ __('Shipping Information') }}</h5>
						<p>{{ __('Already have an account?') }} <a href="{{ route('frontend.login') }}">{{ __('login') }}</a></p>
						<div class="row">
							<div class="col-md-12">
								<div class="mb-3">
									<input id="name" name="name" type="text" placeholder="{{ __('Name') }}" value="@if(isset(Auth::user()->name)) {{ Auth::user()->name }} @endif" class="form-control parsley-validated" data-required="true">
									<span class="text-danger error-text name_error"></span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="mb-3">
									<input id="email" name="email" type="email" placeholder="{{ __('Email Address') }}" value="@if(isset(Auth::user()->email)) {{ Auth::user()->email }} @endif" class="form-control parsley-validated" data-required="true">
									<span class="text-danger error-text email_error"></span>
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<input id="phone" name="phone" type="text" placeholder="{{ __('Phone') }}" value="@if(isset(Auth::user()->phone)) {{ Auth::user()->phone }} @endif" class="form-control parsley-validated" data-required="true">
									<span class="text-danger error-text phone_error"></span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="mb-3">
									<select id="country" name="country" class="form-control parsley-validated" data-required="true">
									<option value="">{{ __('Country') }}</option>
									@foreach($country_list as $row)
									<option value="{{ $row->country_name }}" {{ $row->country_name == "Bangladesh" ? 'selected':'' }}>
										{{ $row->country_name }}
									</option>
									@endforeach
									</select>
									<span class="text-danger error-text country_error"></span>
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<input id="state" name="state" type="text" placeholder="{{ __('State') }}" class="form-control parsley-validated" data-required="true">
									<span class="text-danger error-text state_error"></span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="mb-3">
									<input id="zip_code" name="zip_code" type="text" placeholder="{{ __('Zip Code') }}" class="form-control parsley-validated" data-required="true">
									<span class="text-danger error-text zip_code_error"></span>
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<input id="city" name="city" type="text" placeholder="{{ __('City') }}" class="form-control parsley-validated" data-required="true">
									<span class="text-danger error-text city_error"></span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="mb-3">
									<textarea id="address" name="address" placeholder="{{ __('Address') }}" rows="2" class="form-control parsley-validated" data-required="true">@if(isset(Auth::user()->address)) {{ Auth::user()->address }} @endif</textarea>
									<span class="text-danger error-text address_error"></span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="checkboxlist">
									<label class="checkbox-title">
										<input id="new_account" name="new_account" type="checkbox">{{ __('Register an account with above information?') }}
									</label>
								</div>
								@if ($errors->has('password'))
								<span class="text-danger">{{ $errors->first('password') }}</span>
								@endif
							</div>
						</div>

						<div class="row hideclass" id="new_account_pass">
							<div class="col-md-6">
								<div class="mb-3">
									<input type="password" name="password" id="password" class="form-control" placeholder="{{ __('Password') }}">
									<span class="text-danger error-text password_error"></span>
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="{{ __('Confirm password') }}">
								</div>
							</div>
						</div>

						<h5 class="mt10">{{ __('Payment Method') }}</h5>
						<div class="row">
							<div class="col-md-12">
								<span class="text-danger error-text payment_method_error"></span>
								@if($gtext['stripe_isenable'] == 1)
								<div class="checkboxlist">
									<label class="checkbox-title">
										<input id="payment_method_stripe" name="payment_method" type="radio" value="3">{{ __('Pay online via Stripe') }}
									</label>
								</div>
								<div id="pay_stripe" class="row hideclass">
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-12">
												<div class="mb-3">
													<div class="form-control" id="card-element"></div>
													<span class="card-errors" id="card-errors"></span>
												</div>
											</div>
										</div>
									</div>
								</div>
								@endif

								@if($gtext['cod_isenable'] == 1)
								<div class="checkboxlist">
									<label class="checkbox-title">
										<input id="payment_method_cod" name="payment_method" type="radio" value="1">{{ __('Cash on Delivery (COD)') }}
									</label>
								</div>
								<p id="pay_cod" class="hideclass">{{ $gtext['cod_description'] }}</p>
								@endif

								@if($gtext['bank_isenable'] == 1)
								<div class="checkboxlist">
									<label class="checkbox-title">
										<input id="payment_method_bank" name="payment_method" type="radio" value="2">{{ __('Bank Transfer') }}
									</label>
								</div>
								<p id="pay_bank" class="hideclass">{{ $gtext['bank_description'] }}</p>
								@endif
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="mb-3">
									<textarea name="comments" class="form-control" placeholder="Note" rows="2"></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-5">
						<div class="carttotals-card">
							<div class="carttotals-head">{{ __('Order Summary') }}</div>
							<div class="carttotals-body">
								<table class="table">
									<tbody>
										@foreach(Cart::instance('shopping')->content() as $row)
											@php

											$row->setTaxRate($tax_rate);
											Cart::instance('shopping')->update($row->rowId, $row->qty);

											if($row->options->color == '0'){
												$color = '&nbsp;';
											}else{
												$color = 'Color: '.$row->options->color.'&nbsp;';
											}

											if($row->options->size == '0'){
												$size = '&nbsp;';
											}else{
												$size = 'Size: '.$row->options->size;
											}

											@endphp

											@if($gtext['currency_position'] == 'left')
											<tr>
												<td>
													<p class="title">{{ $row->name }}</p>
													<p class="sub-title">@php echo $color.$size; @endphp</p>
												</td>
												<td>
													<p class="price">{{ $gtext['currency_icon'] }}{{ number_format($row->price*$row->qty) }}</p>
													<p class="sub-price">{{ $gtext['currency_icon'] }}{{ $row->price }} x {{ $row->qty }}</p>
												</td>
											</tr>
											@else
											<tr>
												<td>
													<p class="title">{{ $row->name }}</p>
													<p class="sub-title">@php echo $color.$size; @endphp</p>
												</td>
												<td>
													<p class="price">{{ number_format($row->price*$row->qty) }}{{ $gtext['currency_icon'] }}</p>
													<p class="sub-price">{{ $row->price }}{{ $gtext['currency_icon'] }} x {{ $row->qty }}</p>
												</td>
											</tr>
											@endif
										@endforeach

										@php
											if($gtext['currency_position'] == 'left'){
												$ShippingFee = $gtext['currency_icon'].'<span class="shipping_fee">0</span>';
												$tax = $gtext['currency_icon'].Cart::instance('shopping')->tax();
												$total = $gtext['currency_icon'].'<span class="total_amount">'.Cart::instance('shopping')->total().'</span>';
											}else{
												$ShippingFee = '<span class="shipping_fee">0</span>'.$gtext['currency_icon'];
												$tax = Cart::instance('shopping')->tax().$gtext['currency_icon'];
												$total = '<span class="total_amount">'.Cart::instance('shopping')->total().'</span>'.$gtext['currency_icon'];
											}
										@endphp

										<tr><td colspan="2"><span class="title">{{ __('Shipping Fee') }}</span><span class="price">@php echo $ShippingFee; @endphp</span></td></tr>
										<tr><td colspan="2"><span class="title">{{ __('Tax') }}</span><span class="price">{{ $tax }}</span></td></tr>
										<tr><td colspan="2"><span class="total">{{ __('Total') }}</span><span class="total-price">@php echo $total; @endphp</span></td></tr>
									</tbody>
								</table>
								@if(count($shipping_list)>0)
								<h5>{{ __('Shipping Method') }}</h5>
								<div class="row">
									<div class="col-md-12">
										<span class="text-danger error-text shipping_method_error"></span>
										@foreach($shipping_list as $row)
											@php
												if($gtext['currency_position'] == 'left'){
													$shipping_fee = $gtext['currency_icon'].$row->shipping_fee;
												}else{
													$shipping_fee = $row->shipping_fee.$gtext['currency_icon'];
												}
											@endphp
											<div class="checkboxlist">
												<label class="checkbox-title">
													<input data-shippingfee="{{ $row->shipping_fee }}" data-total="{{ Cart::instance('shopping')->total() }}" class="shipping_method" name="shipping_method" type="radio" value="{{ $row->id }}">{{ $row->title }} - ({{ __('Shipping Fee') }}: {{ $shipping_fee }})
												</label>
											</div>
										@endforeach
									</div>
								</div>
								@endif
								<input name="customer_id" type="hidden" value="@if(isset(Auth::user()->id)) {{ Auth::user()->id }} @endif" />
								<a id="checkout_submit_form" href="javascript:void(0);" class="btn theme-btn mt10 checkout_btn">{{ __('Checkout') }}</a>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<!-- /Checkout/ -->

@endsection

@push('scripts')
<script src="{{asset('public/frontend/js/parsley.min.js')}}"></script>
<script type="text/javascript">
var validCardNumer = 0;
var TEXT = [];
	TEXT['Please type valid card number'] = "{{ __('Please type valid card number') }}";
</script>
@if($gtext['stripe_isenable'] == 1)
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
	var isenable_stripe = "{{ $gtext['stripe_isenable'] }}";
	var stripe_key = "{{ $gtext['stripe_key'] }}";
</script>
<script src="{{asset('public/frontend/pages/payment_method.js')}}"></script>
@endif
<script src="{{asset('public/frontend/pages/checkout.js')}}"></script>
@endpush
