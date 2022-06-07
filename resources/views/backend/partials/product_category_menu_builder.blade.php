@foreach($product_category_datalist as $row)
<li>
	<label class="checkbox-title">
		<input type="checkbox" class="product-category-menu-item" name="product-category-menu-item" value="{{ $row->id }}">{{ $row->name }}
	</label>
</li>
@endforeach
<div class="menu_pagination ProductCategoryMenuBuilder">
{{ $product_category_datalist->links() }}
</div>