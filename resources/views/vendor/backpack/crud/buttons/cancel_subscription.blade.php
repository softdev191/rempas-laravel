@if(isset($entry))
	@if($entry->status == 'Cancelled')
		<a href="javascript:;" disabled="disabled" class="btn btn-xs btn-danger"  title="This subscription has been cancelled">
			<i class="fa fa-close"></i>
		</a>
	@else
		<a href="{{ backpack_url('subscription/cancel/'.$entry->getKey()) }}" class="btn btn-xs btn-danger"  title="Cancel this subscription">
			<i class="fa fa-close"></i>
		</a>
	@endif
@endif
