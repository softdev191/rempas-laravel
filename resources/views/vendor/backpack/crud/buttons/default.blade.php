@if(isset($entry))
	@if(($user->company_id == $entry->company_id) && ($entry->is_default == 1))
		<a class="btn btn-xs btn-danger" disabled="disabled" href="javascript:void(0)" title="Default credit group">
			<i class="fa fa-btn fa-check-circle"></i>
		</a>
	@else
		<a href="{{ url($crud->route.'/'.$entry->getKey().'/default') }}" class="btn btn-xs btn-danger"  title="Mark as default credit price type">
			<i class="fa fa-btn fa-check-circle"></i>
		</a>
	@endif
@endif
