@if(isset($entry))
	@if(!$entry->is_default)
            @if($entry->owner)
		<a href="{{ url($entry->domain_link.'/admin/company/'.$entry->getKey().'/switch-account') }}" target="_blank" class="btn btn-xs btn-danger"  title="Login as this company">
			<i class="fa fa-btn fa-user"></i>
		</a>
            @else
                <a href="javascript:;" target="_blank" class="btn btn-xs btn-danger" disabled="disabled" title="Please complete company registration process.">
			<i class="fa fa-btn fa-user"></i>
		</a>
            @endif
	@endif
@endif
