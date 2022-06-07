<div class="table-responsive">
	<table class="table table-borderless table-theme" style="width:100%;">
		<thead>
			<tr>
				<th class="text-left" style="width:8%">{{ __('Order#') }}</th>
				<th class="text-left" style="width:8%">{{ __('Order Date') }}</th>
				<th class="text-left" style="width:14%">{{ __('Customer') }} </th>
				<th class="text-center" style="width:10%">{{ __('Subtotal') }}</th>
				<th class="text-center" style="width:5%">{{ __('Tax') }}</th>
				<th class="text-center" style="width:10%">{{ __('Shipping Fee') }}</th>
				<th class="text-center" style="width:10%">{{ __('Total Amount') }}</th>
				<th class="text-center" style="width:10%">{{ __('Payment Method') }}</th>
				<th class="text-center" style="width:10%">{{ __('Payment Status') }}</th>
				<th class="text-center" style="width:10%">{{ __('Order Status') }}</th>
				<th class="text-center" style="width:5%">{{ __('Action') }}</th>
			</tr>
		</thead>
		<tbody>
			@if (count($datalist)>0)
			@php $gtext = gtext(); @endphp
			@foreach($datalist as $row)
			@php
			$sub_total = $row->total_amount;
			$tax = $row->tax;
			$shipping_fee = $row->shipping_fee;
			
			$total_amount = $row->total_amount + $row->tax + $row->shipping_fee;
			
			@endphp
			<tr>
				<td class="text-left"><a href="{{ route('seller.order', [$row->id]) }}">{{ $row->order_no }}</a></td>
				<td class="text-left">{{ date('d-m-Y', strtotime($row->created_at)) }}</td>

				@if ($row->customer_id != '')
				<td class="text-left">{{ $row->name }}</td>
				@else
				<td class="text-left">{{ __('Guest User') }}</td>
				@endif

				@if($gtext['currency_position'] == 'left')
				<td class="text-center">{{ $gtext['currency_icon'] }}{{ number_format($sub_total, 2) }}</td>
				@else
				<td class="text-center">{{ number_format($sub_total, 2) }}{{ $gtext['currency_icon'] }}</td>
				@endif
				
				@if($gtext['currency_position'] == 'left')
				<td class="text-center">{{ $gtext['currency_icon'] }}{{ number_format($tax, 2) }}</td>
				@else
				<td class="text-center">{{ number_format($tax, 2) }}{{ $gtext['currency_icon'] }}</td>
				@endif
				
				@if($gtext['currency_position'] == 'left')
				<td class="text-center">{{ $gtext['currency_icon'] }}{{ number_format($shipping_fee, 2) }}</td>
				@else
				<td class="text-center">{{ number_format($shipping_fee, 2) }}{{ $gtext['currency_icon'] }}</td>
				@endif
				
				@if($gtext['currency_position'] == 'left')
				<td class="text-center">{{ $gtext['currency_icon'] }}{{ number_format($total_amount, 2) }}</td>
				@else
				<td class="text-center">{{ number_format($total_amount, 2) }}{{ $gtext['currency_icon'] }}</td>
				@endif
				
				<td class="text-center">{{ $row->method_name }}</td>
				<td class="text-center"><span class="status_btn pstatus_{{ $row->payment_status_id }}">{{ $row->pstatus_name }}</span></td>
				<td class="text-center"><span class="status_btn ostatus_{{ $row->order_status_id }}">{{ $row->ostatus_name }}</span></td>
				
				<td class="text-center">
					<div class="btn-group action-group">
						<a class="action-btn" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
						<div class="dropdown-menu dropdown-menu-right">
							<a class="dropdown-item" href="{{ route('seller.order', [$row->id]) }}">{{ __('View') }}</a>
							<a class="dropdown-item" href="{{ route('frontend.order-invoice', [$row->id, $row->order_no]) }}">{{ __('Invoice') }}</a>
							<a onclick="onDelete({{ $row->id }})" class="dropdown-item" href="javascript:void(0);">{{ __('Delete') }}</a>
						</div>
					</div>
				</td>
			</tr>
			@endforeach
			@else
			<tr>
				<td class="text-center" colspan="11">{{ __('No data available') }}</td>
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