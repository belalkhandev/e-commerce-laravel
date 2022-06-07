<ul class="tabs-nav">
	<li><a href="{{ route('backend.product', [$datalist['id']]) }}"><i class="fa fa-truck"></i>{{ __('Product') }}</a></li>
	<li><a href="{{ route('backend.price', [$datalist['id']]) }}"><i class="fa fa-money"></i>{{ __('Price') }}</a></li>
	<li><a href="{{ route('backend.inventory', [$datalist['id']]) }}"><i class="fa fa-balance-scale"></i>{{ __('Inventory') }}</a></li>
	<li><a href="{{ route('backend.product-images', [$datalist['id']]) }}"><i class="fa fa-picture-o"></i>{{ __('Multiple Images') }}</a></li>
	<li><a href="{{ route('backend.variations', [$datalist['id']]) }}"><i class="fa fa-hourglass-end"></i>{{ __('Variations') }}</a></li>
	<li><a href="{{ route('backend.related-products', [$datalist['id']]) }}"><i class="fa fa-compass"></i>{{ __('Related Products') }}</a></li>
	<li><a href="{{ route('backend.product-seo', [$datalist['id']]) }}"><i class="fa fa-rocket"></i>{{ __('SEO') }}</a></li>
</ul>