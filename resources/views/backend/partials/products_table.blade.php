<div class="table-responsive">
	<table class="table table-borderless table-theme" style="width:100%;">
		<thead>
			<tr>
				<th class="checkboxlist text-center" style="width:5%"><input class="tp-check-all checkAll" type="checkbox"></th>
				<th class="text-left" style="width:21%">{{ __('Product Name') }}</th>
				<th class="text-left" style="width:15%">{{ __('Category') }}</th>
				<th class="text-left" style="width:15%">{{ __('Brand') }}</th>
				<th class="text-center" style="width:10%">{{ __('Store') }}</th>
				<th class="text-center" style="width:10%">{{ __('Language') }}</th>
				<th class="text-center" style="width:8%">{{ __('Image') }} </th>
				<th class="text-center" style="width:8%">{{ __('Status') }}</th>
				<th class="text-center" style="width:8%">{{ __('Action') }}</th>
			</tr>
		</thead>
		<tbody>
			@if (count($datalist)>0)
			@foreach($datalist as $row)
			<tr>
				<td class="checkboxlist text-center"><input name="item_ids[]" value="{{ $row->id }}" class="tp-checkbox selected_item" type="checkbox"></td> 
				
				<td class="text-left"><a href="{{ route('backend.product', [$row->id]) }}" title="{{ __('Edit') }}">{{ $row->title }}</a></td>
				<td class="text-left">{{ $row->category_name }}</td>
				@if($row->brand_id == '0')
				<td class="text-left">No Brand</td>
				@else
				<td class="text-left">{{ $row->brand_name }}</td>
				@endif
				
				<td class="text-center">{{ $row->shop_name }}</td>
				<td class="text-center">{{ $row->language_name }}</td>
				
				@if ($row->f_thumbnail != '')
				<td class="text-center"><div class="table_col_image"><img src="{{ asset('public') }}/media/{{ $row->f_thumbnail }}" /></div></td>
				@else
				<td class="text-center"><div class="table_col_image"><img src="{{ asset('public') }}/backend/images/album_icon.png" /></div></td>
				@endif
				
				@if ($row->is_publish == 1)
				<td class="text-center"><span class="enable_btn">{{ $row->status }}</span></td>
				@else
				<td class="text-center"><span class="disable_btn">{{ $row->status }}</span></td>
				@endif
				<td class="text-center">
					<div class="btn-group action-group">
						<a class="action-btn" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
						<div class="dropdown-menu dropdown-menu-right">
							<a class="dropdown-item" href="{{ route('backend.product', [$row->id]) }}">{{ __('Edit') }}</a>
							<a onclick="onDelete({{ $row->id }})" class="dropdown-item" href="javascript:void(0);">{{ __('Delete') }}</a>
						</div>
					</div>
				</td>
			</tr>
			@endforeach
			@else
			<tr>
				<td class="text-center" colspan="9">{{ __('No data available') }}</td>
			</tr>
			@endif
		</tbody>
	</table>
</div>
<div class="row mt-15">
	<div class="col-lg-12">
		{{ $datalist->links() }}
	</div>
</div>