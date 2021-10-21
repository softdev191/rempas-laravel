<div class="user-panel">
  	<a class="pull-left image" href="{{ route('account.info') }}">
    	<img src="{{ asset('images/avatar.jpg') }}" class="img-circle" alt="">
  	</a>
  	<div class="pull-left info">
    	<p>
    		<a href="{{ route('account.info') }}">{{ $user->name }}</a>
    	</p>
    	<small>
    		<small>
    			<a href="{{ route('account.info') }}">
    				<span>
    					<i class="fa fa-user-circle-o"></i> 
    					{{ trans('backpack::base.my_account') }}
    				</span>
    				</a> 
    		</small>
    	</small>
  	</div>
</div>