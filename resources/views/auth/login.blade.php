@extends('layouts.app')

@section('title', __('Login'))

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
					@if (session('message'))
						<div class="alert alert-danger">{{ session('message') }}</div>
					@endif
					<form id="login_form" method="POST" action="{{ route('login') }}">
						@csrf
						
						@if($errors->any())
							<ul class="errors-list">
							@foreach($errors->all() as $error)
								<li>{{$error}}</li>
							@endforeach
							</ul>
						@endif
						
						<div class="form-group">
							<input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('Email Address') }}" value="{{ old('email') }}" required autocomplete="email" autofocus>
						</div>
						<div class="form-group">
							<input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('Password') }}" required autocomplete="current-password">
						</div>
						<div class="tw_checkbox checkbox_group">
							<input id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
							<label for="remember">{{ __('Remember me') }}</label>
							<span></span>
						</div>
						<input type="submit" class="btn login-btn" value="{{ __('Login') }}">
					</form>
					
					@if (Route::has('password.request'))
					<h3><a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a></h3>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /main Section -->
@endsection

@push('scripts')
@endpush
