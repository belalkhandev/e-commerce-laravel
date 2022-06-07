<div class="table-responsive">
	<table class="table table-borderless table-theme" style="width:100%;">
		<thead>
			<tr>
				<th class="text-center" width="5%">SL#</th>
				<th class="text-left" width="55%">{{ __('Email Address') }}</th>
				<th class="text-center" width="15%">{{ __('Status') }}</th>
				<th class="text-center" width="15%">{{ __('Created At') }}</th>
				<th class="text-center" width="10%">{{ __('Action') }}</th>
			</tr>
		</thead>
		<tbody>
			@if (count($datalist)>0)
			@foreach($datalist as $key => $row)
			<tr>
				<td class="text-center">{{ $key+1 }}</td>
				<td class="text-left">{{ $row->email_address }}</td>
				@if ($row->status == 'subscribed')
				<td class="text-center"><span class="enable_btn">Subscribed</span></td>
				@else
				<td class="text-center"><span class="disable_btn">Unsubscribed</span></td>
				@endif
				<td class="text-center">{{ date('d-m-Y', strtotime($row->created_at)) }}</td> 
				<td class="text-center">
					<div class="btn-group action-group">
						<a class="action-btn" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
						<div class="dropdown-menu dropdown-menu-right">
							<a onclick="onEdit({{ $row->id }})" class="dropdown-item" href="javascript:void(0);">{{ __('Edit') }}</a>
							<a onclick="onDelete({{ $row->id }})" class="dropdown-item" href="javascript:void(0);">{{ __('Delete') }}</a>
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