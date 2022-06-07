@extends('layouts.backend')

@section('title', __('Related Products'))

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
							<div class="col-lg-6">
								{{ __('Related Products') }}
							</div>
							<div class="col-lg-6">
								<div class="float-right">
									<a href="{{ route('seller.products') }}" class="btn warning-btn"><i class="fa fa-reply"></i> {{ __('Back to List') }}</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body tabs-area p-0">
						@include('seller.partials.product_tabs_nav')
						<div class="tabs-body">
							<div class="row">
								<div class="col-lg-7">
									<a onClick="onAddRelatedProductsModalView()" href="javascript:void(0);" class="btn blue-btn mr-10"><i class="fa fa-plus"></i> {{ __('Add New') }}</a>
								</div>
								<div class="col-lg-5">
									<div class="form-group search-box">
										<input id="search" name="search" type="text" class="form-control" placeholder="{{ __('Search') }}...">
										<button type="submit" onClick="onSearch()" class="btn search-btn">{{ __('Search') }}</button>
									</div>
								</div>
							</div>
							<div id="tp_datalist">
								@include('seller.partials.related_products_table')
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		@endif
	</div>
</div>

<!-- Products modal -->
<div id="global_media_modal_view" class="modal bd-example-modal-lg">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">{{ __('Products') }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body media-content padding-no">
				<div class="container-fluid">
					<div class="row mt-15">
						<div class="col-lg-7"></div>
						<div class="col-lg-5">
							<div class="form-group search-box">
								<input id="search_modal" name="search_modal" type="text" class="form-control" placeholder="{{ __('Search') }}...">
								<button type="submit" onClick="onSearchModal()" class="btn search-btn">{{ __('Search') }}</button>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div id="tp_datalist_modal">
								@include('seller.partials.products_list_for_related_product')
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--/Products modal/-->

<!-- /main Section -->
@endsection

@push('scripts')
<!-- css/js -->
<script type="text/javascript">
var product_id = "{{ $datalist['id'] }}";
var TEXT = [];
	TEXT['Do you really want to delete this record'] = "{{ __('Do you really want to delete this record') }}";
</script>
<script src="{{asset('public/backend/pages/related-products_seller.js')}}"></script>
@endpush