<div class="sidebar mb20">
	<div class="widget mb40">
		<h5 class="widget-title">{{ __('Filter by Category') }}</h5>
		<div class="checkboxlist">
			<ul class="checkbox-list">
				@php echo CategoryListForFilter(); @endphp
			</ul>
		</div>
	</div>
	<div class="widget mb40">
		<h5 class="widget-title">{{ __('Filter by Brand') }}</h5>
		<div class="checkboxlist">
			<ul class="checkbox-list">
				@php echo BrandListForFilter(); @endphp
			</ul>
		</div>
	</div>
	<div class="widget mb40">
		<h5 class="widget-title">{{ __('Filter by Color') }}</h5>
		<ul class="widget-color">
			@php echo ColorListForFilter(); @endphp
		</ul>
	</div>
	<div class="widget">
		<h5 class="widget-title">{{ __('Filter by Size') }}</h5>
		<ul class="widget-size">
			@php echo SizeListForFilter(); @endphp
		</ul>
	</div>
</div>