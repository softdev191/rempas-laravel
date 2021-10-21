@if(isset($entry))
	<a href="{{ url($crud->route.'/'.$entry->getKey().'/resend-password-reset-link') }}" class="btn btn-xs btn-danger"  title="Resend password reset link again.">
		<i class="fa fa-btn fa-envelope"></i>
	</a>
@endif
