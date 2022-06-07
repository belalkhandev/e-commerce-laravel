@php $gtext = gtext(); @endphp
<div class="table-responsive">
	<table class="table table-borderless table-theme" style="width:100%;">
		<thead>
			<tr>
				<th class="text-left" style="width:25%">{{ __('Amount') }} ({{ $gtext['currency_icon'] }})</th>
				<th class="text-left" style="width:25%">{{ __('Fee') }} ({{ $gtext['currency_icon'] }})</th>
				<th class="text-center" style="width:20%">{{ __('Date') }}</th>
				<th class="text-center" style="width:20%">{{ __('Status') }}</th>
				<th class="text-center" style="width:10%">{{ __('Action') }}</th>
			</tr>
		</thead>
		<tbody>
			@if (count($datalist)>0)
			@foreach($datalist as $row)
			<tr>
				@if($gtext['currency_position'] == 'left')
				<td class="text-left">{{ $gtext['currency_icon'] }}{{ number_format($row->amount, 2) }}</td>
				@else
				<td class="text-left">{{ number_format($row->amount, 2) }}{{ $gtext['currency_icon'] }}</td>
				@endif
				
				@if($gtext['currency_position'] == 'left')
				<td class="text-left">{{ $gtext['currency_icon'] }}{{ number_format($row->fee_amount, 2) }}</td>
				@else
				<td class="text-left">{{ number_format($row->fee_amount, 2) }}{{ $gtext['currency_icon'] }}</td>
				@endif

				<td class="text-center">{{ date('d-m-Y', strtotime($row->created_at)) }}</td>
				<td class="text-center"><span class="status_btn withdrawal_status_{{ $row->status_id }}">{{ $row->status }}</span></td>

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