@foreach($brand_datalist as $row)
<li>
	<label class="checkbox-title">
		<input type="checkbox" class="brand-menu-item" name="brand-menu-item" value="{{ $row->id }}">{{ $row->name }}
	</label>
</li>
@endforeach
<div class="menu_pagination BrandMenuBuilder">
{{ $brand_datalist->links() }}
</div>