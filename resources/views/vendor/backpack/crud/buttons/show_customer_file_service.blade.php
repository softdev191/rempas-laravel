@if(isset($entry))
	<a href="{{ url($crud->route.'/'.$entry->getKey().'/file-services') }}" class="btn btn-xs btn-danger"  title="Show the file services for this customer">
			<i class="fa fa-btn fa-file-code-o"></i>
		</a>
@endif
