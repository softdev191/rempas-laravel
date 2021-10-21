@if(isset($entry))
	<a href="{{ url($crud->route.'/'.$entry->getKey().'/switch-account') }}" target="_blank" class="btn btn-xs btn-danger"  title="Login as this customer">
		<i class="fa fa-btn fa-user"></i>
	</a>
@endif
