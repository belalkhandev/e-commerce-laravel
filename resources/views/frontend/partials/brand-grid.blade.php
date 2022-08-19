@php $gtext = gtext(); @endphp
<div class="row">
	@if(count($datalist)>0)
	@foreach ($datalist as $row)
	<div class="col-lg-4">
		<div class="item-card mb25">
			<div class="item-image">
				@if($row->labelname != '')
				<ul class="labels-list">
					<li><span class="tplabel" style="background:{{ $row->labelcolor }};">{{ $row->labelname }}</span></li>
				</ul>
				@endif
				<ul class="product-action">
					@if(($row->variation_color != '') || ($row->variation_size != ''))
					<li><a href="{{ route('frontend.product', [$row->id, $row->slug]) }}"><i class="bi bi-cart"></i></a></li>
					@else
					<li><a class="addtocart" data-id="{{ $row->id }}" href="javascript:void(0);"><i class="bi bi-cart"></i></a></li>
					@endif
					<li><a href="{{ route('frontend.product', [$row->id, $row->slug]) }}"><i class="bi bi-zoom-in"></i></a></li>
					<li><a class="addtowishlist" data-id="{{ $row->id }}" href="javascript:void(0);"><i class="bi bi-heart"></i></a></li>
				</ul>
				@if($row->variation_color != '')
				<ul class="color-list">
				  @foreach(explode(',', $row->variation_color) as $color)
				  @php $color_array = explode('|', $color); @endphp
					<li style="background:{{ $color_array[1] }};"></li>
				  @endforeach
				</ul>
				@endif
				<a href="{{ route('frontend.product', [$row->id, $row->slug]) }}"><img src="{{ asset('public/media/'.$row->f_thumbnail) }}" alt="{{ $row->title }}" /></a>
			</div>
			<h4 class="item-title"><a href="{{ route('frontend.product', [$row->id, $row->slug]) }}">{{ str_limit($row->title) }}</a></h4>
			<div class="brand-card">
				<div class="brand">
					<span>{{ __('Brand') }} <a href="{{ route('frontend.brand', [$row->brand_id, str_slug($row->brandname)]) }}">{{ str_limit($row->brandname) }}</a></span>
				</div>
				{{-- <div class="brand">
					<span>{{ __('Sold By') }} <a href="{{ route('frontend.stores', [$row->seller_id, str_slug($row->shop_url)]) }}">{{ str_limit($row->shop_name) }}</a></span>
				</div> --}}
			</div>
			<div class="item-price-card">
				@if($row->sale_price != '')
					@if($gtext['currency_position'] == 'left')
					<div class="item-price">{{ $gtext['currency_icon'] }}{{ number_format($row->sale_price) }}</div>
					@else
					<div class="item-price">{{ number_format($row->sale_price) }}{{ $gtext['currency_icon'] }}</div>
					@endif
				@endif
				@if($row->old_price != '')
					@if($gtext['currency_position'] == 'left')
					<div class="old-item-price">{{ $gtext['currency_icon'] }}{{ number_format($row->old_price) }}</div>
					@else
					<div class="old-item-price">{{ number_format($row->old_price) }}{{ $gtext['currency_icon'] }}</div>
					@endif
				@endif
			</div>
			<div class="rating-wrap">
				<div class="stars-outer">
					<div class="stars-inner" style="width:{{ $row->ReviewPercentage }}%;"></div>
				</div>
				<span class="rating-count">({{ $row->TotalReview }})</span>
			</div>
		</div>
	</div>
	@endforeach
	@else
	<div class="col-lg-12">
		<h5 class="text-center">{{ __('Oops! No product found.') }}</h5>
	</div>
	@endif
</div>
<div class="row mt-15">
	<div class="col-lg-12">
		{{ $datalist->links() }}
	</div>
</div>
