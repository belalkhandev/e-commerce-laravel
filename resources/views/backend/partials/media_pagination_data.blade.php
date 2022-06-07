<div class="row">
	<div class="col-lg-12">
		<ul class="media-view">
			@foreach($media_datalist as $row)
			<li id="media_item_{{ $row->id }}">
				<a onClick="onMediaDelete({{ $row->id }})" class="media-delete" title="{{ __('Delete') }}" href="javascript:void(0);"><i class="fa fa-remove"></i></a>
				<div class="media-preview">
					<a onClick="onMediaModalView({{ $row->id }})" href="javascript:void(0);"><img src="{{ asset('public/media/'.$row->thumbnail) }}" alt="{{ $row->alt_title }}" /></a>
				</div>
			</li>
			@endforeach
		</ul>
	</div>
</div>
<div class="row mt-15">
	<div class="col-lg-12">
		{{ $media_datalist->links() }}
	</div>
</div>