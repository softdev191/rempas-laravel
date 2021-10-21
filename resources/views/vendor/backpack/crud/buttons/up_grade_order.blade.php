@if(isset($entry))
	<a href="{{ url($crud->route.'/'.$entry->getKey().'/up') }}" class="btn btn-xs btn-danger"  title="Move {{ $entry->label }} up">
		<i class="fa fa-btn fa-arrow-up"></i>
	</a>
@endif
