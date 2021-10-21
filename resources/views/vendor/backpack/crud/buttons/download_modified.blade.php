@if($entry->status == 'Completed')
	<a href="{{ url($crud->route.'/'.$entry->getKey().'/download-modified') }}" class="btn btn-xs btn-danger" title="Download modified file">
		<i class="fa fa-download"></i>
	</a>
@endif