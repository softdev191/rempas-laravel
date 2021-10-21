@if(isset($entry))
	<a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" class="btn btn-xs btn-danger"  title="Show the contents of the ticket">
		<i class="fa fa-btn fa-search"></i>
	</a>
@endif
