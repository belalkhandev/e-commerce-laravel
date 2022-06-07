<div class="table-responsive">
	<table class="table table-borderless table-theme" style="width:100%;">
		<thead>
			<tr>
				<th class="text-left" width="20%">{{ __('Language Code') }}</th>
				<th class="text-left" width="25%">{{ __('Language Name') }}</th>
				<th class="text-center" width="15%">{{ __('Default Language') }}</th>
				<th class="text-center" width="15%">{{ __('RTL') }}</th>
				<th class="text-center" width="15%">{{ __('Status') }}</th>
				<th class="text-center" width="10%">{{ __('Action') }}</th>
			</tr>
		</thead>
		<tbody>
			@if (count($datalist)>0)
			@foreach($datalist as $row)
			<tr>
				<td class="text-left">{{ $row->language_code }}</td> 
				<td class="text-left">{{ $row->language_name }}</td> 
				@if ($row->language_default == 1)
				<td class="text-center"><span class="enable_btn">{{ __('YES') }}</span></td>
				@else
				<td class="text-center"><span class="disable_btn">{{ __('NO') }}</span></td>
				@endif
				
				@if ($row->is_rtl == 1)
				<td class="text-center"><span class="enable_btn">{{ __('YES') }}</span></td>
				@else
				<td class="text-center"><span class="disable_btn">{{ __('NO') }}</span></td>
				@endif
				
				@if ($row->status == 1)
				<td class="text-center"><span class="enable_btn">{{ __('Active') }}</span></td>
				@else
				<td class="text-center"><span class="disable_btn">{{ __('Inactive') }}</span></td>
				@endif
				
				<td class="text-center">
					@if ($row->language_code == 'en')
					<div class="btn-group action-group">
						<a class="action-btn" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
						<div class="dropdown-menu dropdown-menu-right">
							<a onclick="onEdit({{ $row->id }})" class="dropdown-item" href="javascript:void(0);">{{ __('Edit') }}</a>
						</div>
					</div>
					@else
					<div class="btn-group action-group">
						<a class="action-btn" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
						<div class="dropdown-menu dropdown-menu-right">
							<a onclick="onEdit({{ $row->id }})" class="dropdown-item" href="javascript:void(0);">{{ __('Edit') }}</a>
							<a onclick="onDelete({{ $row->id }}, '{{ $row->language_code }}')" class="dropdown-item" href="javascript:void(0);">{{ __('Delete') }}</a>
						</div>
					</div>
					@endif
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