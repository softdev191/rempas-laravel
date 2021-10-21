@if(isset($entry))
    @if(isset($entry->tickets))
            <a href="{{ backpack_url('tickets/'.$entry->tickets->id.'/edit') }}" class="btn btn-xs btn-danger" title="Tickets">
                <i class="fa fa-btn fa-comment"></i>
            </a>
        @else
            <a href="{{ backpack_url('file-service/'.$entry->id.'/create-ticket') }}" class="btn btn-xs btn-danger" title="Tickets">
                <i class="fa fa-btn fa-comment"></i>
            </a>
    @endif
	
@endif


