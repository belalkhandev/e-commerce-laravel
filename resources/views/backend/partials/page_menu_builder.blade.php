@foreach($page_datalist as $row)
<li>
	<label class="checkbox-title">
		<input type="checkbox" class="page-menu-item" name="page-menu-item" value="{{ $row->id }}">{{ $row->title }}
	</label>
</li>
@endforeach
<div class="menu_pagination PageMenuBuilder">
{{ $page_datalist->links() }}
</div>