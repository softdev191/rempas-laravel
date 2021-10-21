@if(isset($entry))
	@if($entry->status == 'Cancelled')
		<a href="javascript:;" disabled="disabled" class="btn btn-xs btn-danger"  title="This subscription has been cancelled">
			<i class="fa fa-ban"></i>
		</a>
	@else
		<a href="{{ backpack_url('subscription/immediate/'.$entry->getKey()) }}" class="btn btn-xs btn-danger"  title="Cancel this subscription immediately">
			<i class="fa fa-ban"></i>
		</a>
	@endif
@endif
