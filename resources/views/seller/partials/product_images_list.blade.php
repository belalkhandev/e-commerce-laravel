
<div class="row image_list">
	<div class="col-md-12">
		@if(count($imagelist)>0)
		@foreach($imagelist as $row)
		<div class="select-image tp_thumb">
			<div class="inner-image">
				<img src="{{ asset('public') }}/media/{{ $row->thumbnail }}" />
			</div>
			<a onClick="onDelete({{ $row->id }})" class="media-image-remove" href="javascript:void(0);"><i class="fa fa-remove"></i></a>
		</div>
		@endforeach
		@else
		<h5 class="text-center">{{ __('No data available') }}</h5>
		@endif
	</div>
</div>

<div class="row mt-15">
	<div class="col-lg-12 tp_pagination">
		{{ $imagelist->links() }}
	</div>
</div>