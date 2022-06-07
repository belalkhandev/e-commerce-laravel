<div class="table-responsive">
	<table class="table table-borderless table-theme" style="width:100%;">
		<thead>
			<tr>
				<th class="text-center" width="5%">{{ __('SL') }}</th>
				<th class="text-left" width="35%">{{ __('Type') }}</th>
				<th class="text-center" width="25%">{{ __('Width') }}</th>
				<th class="text-center" width="25%">{{ __('Height') }}</th>
				<th class="text-center" width="10%">{{ __('Action') }}</th>
			</tr>
		</thead>
		<tbody>
			@if (count($datalist)>0)
			@foreach($datalist as $key => $row)
			<tr>
				<td class="text-center">{{$key+1}}</td>
				<td class="text-left">{{ $row->media_type }}</td>
				<td class="text-center">{{ $row->media_width }}</td>
				<td class="text-center">{{ $row->media_height }}</td>
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
				<td class="text-center" colspan="5">{{ __('No data available') }}</td>
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