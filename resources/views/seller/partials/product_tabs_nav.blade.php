<ul class="tabs-nav">
	<li><a href="{{ route('seller.product', [$datalist['id']]) }}"><i class="fa fa-truck"></i>{{ __('Product') }}</a></li>
	<li><a href="{{ route('seller.price', [$datalist['id']]) }}"><i class="fa fa-money"></i>{{ __('Price') }}</a></li>
	<li><a href="{{ route('seller.inventory', [$datalist['id']]) }}"><i class="fa fa-balance-scale"></i>{{ __('Inventory') }}</a></li>
	<li><a href="{{ route('seller.product-images', [$datalist['id']]) }}"><i class="fa fa-picture-o"></i>{{ __('Multiple Images') }}</a></li>
	<li><a href="{{ route('seller.variations', [$datalist['id']]) }}"><i class="fa fa-hourglass-end"></i>{{ __('Variations') }}</a></li>
	<li><a href="{{ route('seller.related-products', [$datalist['id']]) }}"><i class="fa fa-compass"></i>{{ __('Related Products') }}</a></li>
	<li><a href="{{ route('seller.product-seo', [$datalist['id']]) }}"><i class="fa fa-rocket"></i>{{ __('SEO') }}</a></li>
</ul>