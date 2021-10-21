@if(isset($entry))
	@if(!$entry->is_default)
            @if($entry->owner)
		<a href="{{ backpack_url('subscription?company='.$entry->getKey()) }}" class="btn btn-xs btn-danger"  title="View all subscriptions for this company">
			<i class="fa fa-btn fa-list"></i>
		</a>
            @else
                <a href="javascript:;" class="btn btn-xs btn-danger" disabled="disabled"  title="Please complete company registration process.">
			<i class="fa fa-btn fa-list"></i>
		</a>
            @endif
	@endif
@endif
