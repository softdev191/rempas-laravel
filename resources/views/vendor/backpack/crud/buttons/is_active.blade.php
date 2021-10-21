@if(isset($entry))
	@php $companyUser = $entry->owner; @endphp
	@if(isset($companyUser->is_active)) 
		@if( $companyUser->is_active== 1)
			<a href="{{ url($crud->route.'/'.$entry->getKey().'/account-activate') }}" target="" class="btn btn-xs btn-danger"  title="Active">
				<i class="fa fa-btn fa-thumbs-up"></i>
			</a>
		@else
			<a href="{{ url($crud->route.'/'.$entry->getKey().'/account-activate') }}" target="" class="btn btn-xs btn-danger"  title="Deactive">
				<i class="fa fa-btn fa-thumbs-down"></i>
			</a>
		@endif
	@endif
@endif