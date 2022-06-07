@extends('layouts.backend')

@section('title', __('Theme Register'))

@section('content')
<!-- main Section -->
<div class="main-body">
	<div class="container-fluid">
		<div class="row mt-25">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header">{{ __('Settings') }}</div>
					<div class="card-body tabs-area p-0">
						@include('backend.partials.settings_tabs_nav')
						<div class="tabs-body">
							<div id="PurchaseCodeId">
								@include('backend.partials.purchase_code')
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /main Section -->
@endsection

@push('scripts')
<script type="text/javascript">
var TEXT = [];
	TEXT['Do you really want to deregister the theme'] = "{{ __('Do you really want to deregister the theme') }}";
	TEXT['Please fill out required field'] = "{{ __('Please fill out required field') }}";
</script>
<script src="{{asset('public/backend/pages/theme-register.js')}}"></script>
@endpush