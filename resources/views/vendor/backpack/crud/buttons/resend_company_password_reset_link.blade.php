@if(isset($entry))
	@if(!$entry->is_default)
            
            @if($entry->owner)
		 <a href="{{ url($crud->route.'/'.$entry->getKey().'/resend-password-reset-link') }}" class="btn btn-xs btn-danger"  title="Resend password reset link again.">
                    <i class="fa fa-btn fa-envelope"></i>
		</a>
            @else
                <a href="javscript:void;" class="btn btn-xs btn-danger" disabled="disabled"  title="Please complete company registration process.">
                    <i class="fa fa-btn fa-envelope"></i>
		</a>
            @endif
	@endif
@endif
