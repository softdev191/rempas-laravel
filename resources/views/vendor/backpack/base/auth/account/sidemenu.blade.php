<div class="box">
    <div class="box-body box-profile">
	    <img class="profile-user-img img-responsive img-circle" src="{{ asset('images/avatar.jpg') }}">
	    <h3 class="profile-username text-center">{{ $user->name }}</h3>
	</div>

	<hr class="m-t-0 m-b-0">

	<ul class="nav nav-pills nav-stacked">

	  <li role="presentation"
		@if (Request::route()->getName() == 'account.info')
	  	class="active"
	  	@endif
	  	><a href="{{ route('account.info') }}">{{ trans('backpack::base.update_account_info') }}</a></li>

	  <li role="presentation"
		@if (Request::route()->getName() == 'account.password')
	  	class="active"
	  	@endif
	  	><a href="{{ route('account.password') }}">{{ trans('backpack::base.change_password') }}</a></li>

	</ul>
</div>
