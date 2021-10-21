@if(isset($entry))
	<a href="{{ backpack_url('subscription-payment?company='.$entry->user->company_id.'&subscription='.$entry->getKey()) }}" class="btn btn-xs btn-danger"  title="View all billings payments for this subscription">
		<i class="fa fa-btn fa-list"></i>
	</a>
@endif
