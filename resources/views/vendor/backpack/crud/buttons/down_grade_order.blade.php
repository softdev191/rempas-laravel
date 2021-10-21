@if(isset($entry))
	<a href="{{ url($crud->route.'/'.$entry->getKey().'/down') }}" class="btn btn-xs btn-danger"  title="Move {{ $entry->label }} down">
		<i class="fa fa-btn fa-arrow-down"></i>
	</a>
@endif
