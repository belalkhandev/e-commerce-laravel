@foreach($product_datalist as $row)
<li>
	<label class="checkbox-title">
		<input type="checkbox" class="product-menu-item" name="product-menu-item" value="{{ $row->id }}">{{ $row->title }}
	</label>
</li>
@endforeach
<div class="menu_pagination ProductMenuBuilder">
{{ $product_datalist->links() }}
</div>