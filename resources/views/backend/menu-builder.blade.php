@extends('layouts.backend')

@section('title', __('Menu'))

@section('content')
<!-- main Section -->
<div class="main-body">
	<div class="container-fluid">
		@php $vipc = vipc(); @endphp
		@if($vipc['bkey'] == 0) 
		@include('backend.partials.vipc')
		@else
		<div class="row mt-25">
			<div class="col-lg-4">
				<div class="card">
					<div class="card-header">
						<span>{{ __('Add menu items') }}</span>
						<em>{{ __('Only selected language menu list') }}</em>
					</div>
					<div class="card-body padding-no">
						<div id="add_menu_items_accordion" class="accordion-container">
							<ul class="outer-border">
								<!--Pages-->
								<li id="add_post_type_page" class="accordion-section">
									<h3 class="accordion-section-title" data-toggle="collapse" data-target="#collapse_post_type_page" aria-expanded="true" aria-controls="collapse_post_type_page">{{ __('Pages') }}</h3>
									<div id="collapse_post_type_page" class="collapse show accordion-section-content" aria-labelledby="add_post_type_page" data-parent="#add_menu_items_accordion">
										<div class="content-box checkboxlist">
											<div class="form-group search-box">
												<input id="search_page_menu" name="search_page_menu" type="text" class="form-control" placeholder="{{ __('Search') }}...">
												<button type="submit" onclick="onPageMenuBuilderSearch()" class="btn search-btn">{{ __('Search') }}</button>
											</div>
											<ul id="page_menu_builder_id" class="itemchecklist">
												@include('backend.partials.page_menu_builder')
											</ul>
											<div class="button-controls">
												<span class="list-controls">
													<input type="checkbox" id="page-tab" class="pageCheckAll">
													<label for="page-tab">{{ __('Select All') }}</label>
												</span>
												<span class="add-to-menu">
													<a onclick="submitPageMenu()" href="javascript:void(0);" class="btn blue-btn">{{ __('Add to Menu') }}</a>
												</span>
											</div>
										</div>
									</div>
								</li>
								<!--/Pages/-->
								
								<!--Brands-->
								<li id="add_brand" class="accordion-section">
									<h3 class="accordion-section-title collapsed" data-toggle="collapse" data-target="#collapse_brand" aria-expanded="false" aria-controls="collapse_brand">{{ __('Brands') }}</h3>
									<div id="collapse_brand" class="collapse accordion-section-content" aria-labelledby="add_brand" data-parent="#add_menu_items_accordion">
										<div class="content-box checkboxlist">
											<div class="form-group search-box">
												<input id="search_brand_menu" name="search_brand_menu" type="text" class="form-control" placeholder="{{ __('Search') }}...">
												<button type="submit" onclick="onBrandMenuBuilderSearch()" class="btn search-btn">{{ __('Search') }}</button>
											</div>
											<ul id="category_menu_builder_id" class="itemchecklist">
												@include('backend.partials.brand_menu_builder')
											</ul>
											<div class="button-controls">
												<span class="list-controls">
													<input type="checkbox" id="brand-tab" class="brandCheckAll">
													<label for="brand-tab">{{ __('Select All') }}</label>
												</span>
												<span class="add-to-menu">
													<a onclick="submitBrandMenu()" href="javascript:void(0);" class="btn blue-btn">{{ __('Add to Menu') }}</a>
												</span>
											</div>
										</div>
									</div>
								</li>
								<!--/Brands/-->
								
								<!--Product-->
								<li id="add_product" class="accordion-section">
									<h3 class="accordion-section-title collapsed" data-toggle="collapse" data-target="#collapse_product" aria-expanded="false" aria-controls="collapse_product">{{ __('Products') }}</h3>
									<div id="collapse_product" class="collapse accordion-section-content" aria-labelledby="add_product" data-parent="#add_menu_items_accordion">
										<div class="content-box checkboxlist">
											<div class="form-group search-box">
												<input id="search_product_menu" name="search_product_menu" type="text" class="form-control" placeholder="{{ __('Search') }}...">
												<button type="submit" onclick="onProductMenuBuilderSearch()" class="btn search-btn">{{ __('Search') }}</button>
											</div>
											<ul id="product_menu_builder_id" class="itemchecklist">
												@include('backend.partials.product_menu_builder')
											</ul>
											<div class="button-controls">
												<span class="list-controls">
													<input type="checkbox" id="product-tab" class="productCheckAll">
													<label for="product-tab">{{ __('Select All') }}</label>
												</span>
												<span class="add-to-menu">
													<a onclick="submitProductMenu()" href="javascript:void(0);" class="btn blue-btn">{{ __('Add to Menu') }}</a>
												</span>
											</div>
										</div>
									</div>
								</li>
								<!--/Product/-->
								
								<!--Product Category-->
								<li id="add_product_category" class="accordion-section">
									<h3 class="accordion-section-title collapsed" data-toggle="collapse" data-target="#collapse_product_category" aria-expanded="false" aria-controls="collapse_product_category">{{ __('Categories') }}</h3>
									<div id="collapse_product_category" class="collapse accordion-section-content" aria-labelledby="add_product_category" data-parent="#add_menu_items_accordion">
										<div class="content-box checkboxlist">
											<div class="form-group search-box">
												<input id="search_product_category_menu" name="search_product_category_menu" type="text" class="form-control" placeholder="{{ __('Search') }}...">
												<button type="submit" onclick="onProductCategoryMenuBuilderSearch()" class="btn search-btn">{{ __('Search') }}</button>
											</div>
											<ul id="product_category_menu_builder_id" class="itemchecklist">
												@include('backend.partials.product_category_menu_builder')
											</ul>
											<div class="button-controls">
												<span class="list-controls">
													<input type="checkbox" id="product-category-tab" class="productCategoryCheckAll">
													<label for="product-category-tab">{{ __('Select All') }}</label>
												</span>
												<span class="add-to-menu">
													<a onclick="submitProductCategoryMenu()" href="javascript:void(0);" class="btn blue-btn">{{ __('Add to Menu') }}</a>
												</span>
											</div>
										</div>
									</div>
								</li>
								<!--/Product Category/-->
								
								<!--Custom Links-->
								<li id="add_custom_links" class="accordion-section">
									<h3 class="accordion-section-title collapsed" data-toggle="collapse" data-target="#collapse_custom_links" aria-expanded="false" aria-controls="collapse_custom_links">{{ __('Custom Links') }}</h3>
									<div id="collapse_custom_links" class="collapse accordion-section-content" aria-labelledby="add_custom_links" data-parent="#add_menu_items_accordion">
										<div class="content-box">
											<form novalidate="" data-validate="parsley" id="CustomLinkDataFormId">
												<div class="form-group">
													<label for="url">{{ __('URL') }}<span class="red">*</span></label>
													<input type="text" name="url" id="url" class="form-control parsley-validated" data-required="true" placeholder="https://">
												</div>
												<div class="form-group">
													<label for="link_text">{{ __('Link Text') }}<span class="red">*</span></label>
													<input type="text" name="link_text" id="link_text" class="form-control parsley-validated" data-required="true">
												</div>
												<div class="button-controls">
													<span class="add-to-menu">
														<a id="submit-custom-link-form" href="javascript:void(0);" class="btn blue-btn">{{ __('Add to Menu') }}</a>
													</span>
												</div>
											</form>
										</div>
									</div>
								</li>
								<!--/Custom Links/-->
							</ul>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-lg-8">
				<div class="card">
					<div class="card-header">
						<div class="row">
							<div class="col-lg-8">
								<span>{{ __('Menu structure') }}</span>
								<em><strong>{{ __('Menu Name') }}:</strong> {{ isset($main_menu) ? $main_menu['menu_name'] : '' }}</em>
							</div>
							<div class="col-lg-4">
								<div class="float-right">
									<a onclick="onSortableItemList()" href="javascript:void(0);" class="btn blue-btn mt5">{{ __('Save Menu') }}</a>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div id="menu_management_accordion" class="menu-management">
							<ul id="menulist_id" class="menu-edit">
								@include('backend.partials.make_menu_list')
							</ul>
						</div>
						<div class="row tabs-footer mt-15">
							<div class="col-lg-12">
								<a onclick="onSortableItemList()" href="javascript:void(0);" class="btn blue-btn mt5">{{ __('Save Menu') }}</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		@endif
	</div>
</div>

<!--modal view-->
<div class="modal" id="megamenu_modal_view">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">{{ __('Edit Mega Menu Title') }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form novalidate="" data-validate="parsley" id="MegaMenuTitle_FormId">
				<div class="modal-body">
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="mega_menu_title">{{ __('Title') }}<span class="red">*</span></label>
									<input type="text" name="mega_menu_title" id="mega_menu_title" class="form-control parsley-validated" data-required="true">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group checkboxlist">
									<ul class="itemchecklist">
										<li>
											<label class="checkbox-title">
												<input type="checkbox" name="is_title" id="is_title"> {{ __('Title Enable/Disable') }}
											</label>
										</li>
										<li>
											<label class="checkbox-title">
												<input type="checkbox" name="is_image" id="is_image"> {{ __('Image Enable/Disable') }}
											</label>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div id="mega_menu_image_hide_show" class="form-group dnone">
									<label for="mega_menu_image">{{ __('Image') }}</label>
									<div class="tp-upload-field">
										<input type="text" name="mega_menu_image" id="mega_menu_image" class="form-control" readonly>
										<a onClick="onGlobalMediaModalView()" href="javascript:void(0);" class="tp-upload-btn"><i class="fa fa-window-restore"></i>{{ __('Browse') }}</a>
									</div>
									<em>Recommended image size width: 300px and height: 400px.</em>
									<div id="remove_mega_menu_image" class="select-image">
										<div class="inner-image" id="view_mega_menu_image"></div>
										<a onClick="onMediaImageRemove('mega_menu_image')" class="media-image-remove" href="javascript:void(0);"><i class="fa fa-remove"></i></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<input type="hidden" name="mega_menu_RecordId" id="mega_menu_RecordId" />
				<div class="modal-footer">
					<a id="submit_MegaMenuTitle_form" href="javascript:void(0);" class="btn blue-btn">{{ __('Save') }}</a>
				</div>
			</form>
		</div>
	</div>
</div>
<!--/modal view/-->

<!--Global Media-->
@include('backend.partials.global_media')
<!--/Global Media/-->

<!-- /main Section -->
@endsection

@push('scripts')

<!-- css/js -->
<script type="text/javascript">

var main_menu_id = "{{ isset($main_menu) ? $main_menu['id'] : 0 }}";
var main_menu_lan = "{{ isset($main_menu) ? $main_menu['lan'] : 0 }}";
var media_type = 'Mega_Menu';

var TEXT = [];
	TEXT['Please select record'] = "{{ __('Please select record') }}";
	TEXT['Please fill out required field'] = "{{ __('Please fill out required field') }}";
</script>
<script src="{{asset('public/backend/pages/menu-builder.js')}}"></script>
<script src="{{asset('public/backend/pages/global-media.js')}}"></script>
@endpush