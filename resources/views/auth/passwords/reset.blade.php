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
						<a href="{{ url('/') }}">
							<img src="{{ $gtext['back_logo'] ? asset('public/media/'.$gtext['back_logo']) : asset('public/backend/images/backend-logo.png') }}" alt="logo">
						</a>
					</div>
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
					<form class="text-left" id="login_form" method="POST" action="{{ route('frontend.resetPasswordUpdate') }}">
						@csrf
						
						<input type="hidden" name="token" value="{{ $token }}">
						
						<div class="form-group">
							<input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('Email Address') }}" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
							@if ($errors->has('email'))
							<span class="text-danger">{{ $errors->first('email') }}</span>
							@endif
						</div>
						<div class="form-group">
							<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('Password') }}" name="password" required autocomplete="new-password">
							@if ($errors->has('password'))
							<span class="text-danger">{{ $errors->first('password') }}</span>
							@endif
						</div>
						<div class="form-group">
							<input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="{{ __('Confirm password') }}" required autocomplete="new-password">
						</div>
						@if($gtext['is_recaptcha'] == 1)
						<div class="form-group">
							<div class="g-recaptcha" data-sitekey="{{ $gtext['sitekey'] }}"></div>
							@if ($errors->has('g-recaptcha-response'))
							<span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
							@endif
						</div>
						@endif
						<input type="submit" class="btn login-btn" value="{{ __('Reset Password') }}">
					</form>
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
