<div class="table-responsive">
	<table class="table table-borderless table-theme" style="width:100%;">
		<thead>
			<tr>
				<th class="text-left" style="width:35%">{{ __('Product Name') }}</th>
				<th class="text-center" style="width:15%">{{ __('SKU') }}</th>
				<th class="text-center" style="width:10%">{{ __('Stock Quantity') }}</th>
				<th class="text-center" style="width:10%">{{ __('Manage Stock') }}</th>
				<th class="text-center" style="width:14%">{{ __('Status') }}</th>
				<th class="text-center" style="width:8%">{{ __('Image') }} </th>
				<th class="text-center" style="width:8%">{{ __('Action') }}</th>
			</tr>
		</thead>
		<tbody>
			@if (count($datalist)>0)
			@foreach($datalist as $row)
			<tr>
				<td class="text-left">{{ $row->title }}</td>
				<td class="text-center">{{ $row->sku }}</td>
				<td class="text-center">{{ $row->stock_qty }}</td>

				@if ($row->is_stock == 1)
				<td class="text-center"><span class="enable_btn">{{ __('YES') }}</span></td>
				@else
				<td class="text-center"><span class="disable_btn">{{ __('NO') }}</span></td>
				@endif
				
				@if ($row->stock_status_id == 1)
				<td class="text-center"><span class="enable_btn">{{ __('In Stock') }}</span></td>
				@else
					@if ($row->is_stock == 1)
					<td class="text-center"><span class="disable_btn">{{ __('Out Of Stock') }}</span></td>
					@else
					<td class="text-center"></td>
					@endif
				@endif
				
				@if ($row->f_thumbnail != '')
				<td class="text-center"><div class="table_col_image"><img src="{{ asset('public') }}/media/{{ $row->f_thumbnail }}" /></div></td>
				@else
				<td class="text-center"><div class="table_col_image"><img src="{{ asset('public') }}/backend/images/album_icon.png" /></div></td>
				@endif

				<td class="text-center">
					<div class="btn-group action-group">
						<a class="action-btn" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
						<div class="dropdown-menu dropdown-menu-right">
							<a onclick="onEdit({{ $row->id }})" class="dropdown-item" href="javascript:void(0);">{{ __('Edit') }}</a>
						</div>
					</div>
				</td>
			</tr>
			@endforeach
			@else
			<tr>
				<td class="text-center" colspan="7">{{ __('No data available') }}</td>
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