<div class="table-responsive">
	<table class="table table-borderless table-theme" style="width:100%;">
		<thead>
			<tr>
				<th class="text-left" style="width:65%">{{ __('Product Name') }}</th>
				<th class="text-center" style="width:15%">{{ __('Language') }}</th>
				<th class="text-center" style="width:10%">{{ __('Image') }} </th>
				<th class="text-center" style="width:10%">{{ __('Action') }}</th>
			</tr>
		</thead>
		<tbody>
			@if (count($productlist)>0)
			@foreach($productlist as $row)
			<tr>
				<td class="text-left">{{ $row->title }}</td>
				<td class="text-center">{{ $row->language_name }}</td>
				@if ($row->f_thumbnail != '')
				<td class="text-center"><div class="table_col_image"><img src="{{ asset('public') }}/media/{{ $row->f_thumbnail }}" /></div></td>
				@else
				<td class="text-center"><div class="table_col_image"><img src="{{ asset('public') }}/backend/images/album_icon.png" /></div></td>
				@endif
				<td class="text-center">
					<a onclick="onRelatedProduct({{ $row->id }})" class="editIconBtn" title="{{ __('Add Item') }}" href="javascript:void(0);"><i class="fa fa-plus"></i></a>
				</td>
			</tr>
			@endforeach
			@else
			<tr>
				<td class="text-center" colspan="4">{{ __('No data available') }}</td>
			</tr>
			@endif
		</tbody>
	</table>
</div>
<div class="row mt-15 mb-15">
	<div class="col-lg-12 tp_pagination_modal">
		{{ $productlist->links() }}
	</div>
</div>