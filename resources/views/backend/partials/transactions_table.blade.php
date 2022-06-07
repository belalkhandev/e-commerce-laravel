<div class="table-responsive">
	<table class="table table-borderless table-theme" style="width:100%;">
		<thead>
			<tr>
				<th class="text-left" style="width:12%">{{ __('Order#') }}</th>
				<th class="text-left" style="width:12%">{{ __('Transaction#') }}</th>
				<th class="text-left" style="width:10%">{{ __('Order Date') }}</th>
				<th class="text-left" style="width:15%">{{ __('Customer') }} </th>
				<th class="text-left" style="width:15%">{{ __('Store') }}</th>
				<th class="text-left" style="width:10%">{{ __('Amount') }}</th>
				<th class="text-center" style="width:13%">{{ __('Payment Method') }}</th>
				<th class="text-center" style="width:13%">{{ __('Payment Status') }}</th>
			</tr>
		</thead>
		<tbody>
			@if (count($datalist)>0)
			@php $gtext = gtext(); @endphp
			@foreach($datalist as $row)
			@php
			$total_amount = $row->total_amount + $row->tax + $row->shipping_fee;
			@endphp
			<tr>
				<td class="text-left"><a href="{{ route('backend.order', [$row->id]) }}">{{ $row->order_no }}</a></td>
				<td class="text-left"><a href="{{ route('backend.order', [$row->id]) }}">{{ $row->transaction_no }}</a></td>
				<td class="text-left">{{ date('d-m-Y', strtotime($row->created_at)) }}</td>

				@if ($row->customer_id != '')
				<td class="text-left">{{ $row->name }}</td>
				@else
				<td class="text-left">{{ __('Guest User') }}</td>
				@endif
				
				<td class="text-left">{{ $row->shop_name }}</td>
				
				@if($gtext['currency_position'] == 'left')
				<td class="text-left">{{ $gtext['currency_icon'] }}{{ number_format($total_amount, 2) }}</td>
				@else
				<td class="text-left">{{ number_format($total_amount, 2) }}{{ $gtext['currency_icon'] }}</td>
				@endif
				
				<td class="text-center">{{ $row->method_name }}</td>
				<td class="text-center"><span class="status_btn pstatus_{{ $row->payment_status_id }}">{{ $row->pstatus_name }}</span></td>
			</tr>
			@endforeach
			@else
			<tr>
				<td class="text-center" colspan="8">{{ __('No data available') }}</td>
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