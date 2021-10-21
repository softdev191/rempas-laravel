@if(isset($entry))
        @if($entry->owner && !$entry->owner->hasActiveSubscription())
         <a href="{{ url($crud->route.'/'.$entry->getKey().'/company-trial-subscription') }}" class="btn btn-xs btn-danger" title="Add trial subscription for this company">
                <i class="fa fa-btn fa-money"></i>
            </a>
            
        @else
           <a href="javascript:;" disabled="disabled" class="btn btn-xs btn-danger" title="Please complete company registration process.">
                <i class="fa fa-btn fa-money"></i>
            </a>
        @endif
@endif
