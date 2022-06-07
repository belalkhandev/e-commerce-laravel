<div class="table-responsive">
	<table class="table table-borderless table-theme" style="width:100%;">
		<thead>
			<tr>
				<th class="checkboxlist text-center" style="width:5%"><input class="tp-check-all checkAll" type="checkbox"></th>
				<th class="text-left" style="width:40%">{{ __('Menu Name') }}</th>
				<th class="text-center" style="width:15%">{{ __('Position') }}</th>
				<th class="text-center" style="width:15%">{{ __('Language') }}</th>
				<th class="text-center" style="width:15%">{{ __('Status') }}</th>
				<th class="text-center" style="width:10%">{{ __('Action') }}</th>
			</tr>
		</thead>
		<tbody>
			@if (count($datalist)>0)
			@foreach($datalist as $row)
			<tr>
				<td class="checkboxlist text-center"><input name="item_ids[]" value="{{ $row->id }}" class="tp-checkbox selected_item" type="checkbox"></td> 
				<td class="text-left"><a href="{{ route('backend.menu-builder', [$row->lan, $row->id]) }}" title="{{ __('Customize') }}">{{ $row->menu_name }}</a></td> 
				<td class="text-center">{{ $row->menu_position }}</td>
				<td class="text-center">{{ $row->lan }}</td>
				@if ($row->status_id == 1)
				<td class="text-center"><span class="enable_btn">{{ $row->status }}</span></td>
				@else
				<td class="text-center"><span class="disable_btn">{{ $row->status }}</span></td>
				@endif
				<td class="text-center">
					<div class="btn-group action-group">
						<a class="action-btn" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
						<div class="dropdown-menu dropdown-menu-right">
							<a href="{{ route('backend.menu-builder', [$row->lan, $row->id]) }}" class="dropdown-item">{{ __('Customize') }}</a>
							<a onclick="onEdit({{ $row->id }})" class="dropdown-item" href="javascript:void(0);">{{ __('Edit') }}</a>
							<a onclick="onDelete({{ $row->id }})" class="dropdown-item" href="javascript:void(0);">{{ __('Delete') }}</a>
						</div>
					</div>
				</td>
			</tr>
			@endforeach
			@else
			<tr>
				<td class="text-center" colspan="6">{{ __('No data available') }}</td>
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