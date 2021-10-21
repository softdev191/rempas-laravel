@if(isset($entry))
	@php 
		if($entry->is_public == 1){
	@endphp
		<a href="{{ url($crud->route.'/'.$entry->getKey().'/company-account-type') }}" target="" class="btn btn-xs btn-danger"  title="Public">
			<i class="fa fa-btn fa-users"></i>
		</a>
	@php 
		}else{
	@endphp
		<a href="{{ url($crud->route.'/'.$entry->getKey().'/company-account-type') }}" target="" class="btn btn-xs btn-danger"  title="Private">
			<i class="fa fa-btn fa-lock"></i>
		</a>
	@php
		}
	@endphp
@endif
