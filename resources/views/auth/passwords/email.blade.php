@extends('layouts.app')

@section('title', __('Reset Password'))

@php $gtext = gtext(); @endphp

@section('content')
<!-- main Section -->
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="loginsignup-area">
				<div class="loginsignup text-center">
					<div class="logo">
						<a href="{{ url('/login') }}">
							<img src="{{ $gtext['back_logo'] ? asset('public/media/'.$gtext['back_logo']) : asset('public/backend/images/backend-logo.png') }}" alt="logo">
						</a>
					</div>
					<p>{{ __('Enter your email address below and we will send you a link to reset your password') }}</p>
					@if(Session::has('success'))
					<div class="alert alert-success">
						{{Session::get('success')}}
					</div>
					@endif
					@if(Session::has('fail'))
					<div class="alert alert-danger">
						{{Session::get('fail')}}
					</div>
					@endif
					<form class="text-left" id="login_form" method="POST" action="{{ route('frontend.resetPassword') }}">
						@csrf
						
						<div class="form-group">
							<input name="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('Email Address') }}" value="{{ old('email') }}" required autocomplete="email">
							@if ($errors->has('email'))
							<span class="text-danger">{{ $errors->first('email') }}</span>
							@endif
						</div>

						@if($gtext['is_recaptcha'] == 1)
						<div class="form-group">
							<div class="g-recaptcha" data-sitekey="{{ $gtext['sitekey'] }}"></div>
							@if ($errors->has('g-recaptcha-response'))
							<span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
							@endif
						</div>
						@endif
						<input type="submit" class="btn login-btn" value="{{ __('Send Password Reset Link') }}">
					</form>
					@if (Route::has('frontend.login'))
					<h3><a href="{{ url('/login') }}">{{ __('Back to login') }}</a></h3>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /main Section -->
@endsection

@push('scripts')
@if($gtext['is_recaptcha'] == 1)
<script src='https://www.google.com/recaptcha/api.js' async defer></script>
@endif
@endpush
