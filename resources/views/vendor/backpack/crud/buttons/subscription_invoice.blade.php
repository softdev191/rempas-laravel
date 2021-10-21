@if(isset($entry))
	<a href="{{ url($crud->route.'/'.$entry->getKey().'/invoice') }}" class="btn btn-xs btn-danger"  title="View invoice">
		<i class="fa fa-btn fa-file"></i>
	</a>
@endif
