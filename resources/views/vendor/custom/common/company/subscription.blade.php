@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">Company Subscriptions</span>
        <small>Subscriptions for {{ $company->name }}</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ backpack_url('company') }}" class="text-capitalize">Company</a></li>
	    <li class="active">Subscriptions</li>
	  </ol>
	</section>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<!-- Default box -->
		<a href="{{ backpack_url('company') }}" class="hidden-print">
			<i class="fa fa-angle-double-left"></i> Back to all  <span>companies</span>
		</a><br><br>
                    <form method="POST" action="{{ backpack_url('company/'.$_company->id.'/company-trial-subscription') }}">
		  	@csrf
		  	<div class="box">
			    <div class="box-header with-border">
			      	<h3 class="box-title">Add Trial Subscription</h3>
			    </div>
		    	<div class="box-body row display-flex-wrap" style="display: flex; flex-wrap: wrap;">
		    		<input type="hidden" name="company_id" value="{{ $company->id }}">
					<div class="form-group col-md-6 col-xs-12 required {{ $errors->has('description') ? ' has-error' : '' }}">
					    <label>Description</label>
				        <input name="description" value="" placeholder="Description" class="form-control" type="text">
				        @if ($errors->has('description'))
                            <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
				    </div>
				    <div style="width: 100%"></div>
				    <div class="form-group col-md-6 col-xs-12 required {{ $errors->has('trial_days') ? ' has-error' : '' }}">
					    <label>Days (trial period)</label>
				        <input name="trial_days" value="{{ (old('trial_days'))?old('trial_days'):'' }}" placeholder="Trial days" class="form-control" type="text">
				        @if ($errors->has('trial_days'))
                            <span class="help-block">
                                <strong>{{ $errors->first('trial_days') }}</strong>
                            </span>
                        @endif
				    </div>
				    <div style="width: 100%"></div>
		    	</div><!-- /.box-body -->
			    <div class="box-footer">
	                <div id="saveActions" class="form-group">
					    <div class="btn-group">
					        <button type="submit" class="btn btn-danger">
					            <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
					            <span>Save</span>
					        </button>
					    </div>
					    <a href="{{ backpack_url('company') }}" class="btn btn-default"><span class="fa fa-ban"></span> &nbsp;Cancel</a>
					</div>
		    	</div><!-- /.box-footer-->
		  	</div><!-- /.box -->
		</form>
              
	</div>
</div>
<div class="row">
    <div class="col-md-12">
    	<div class="box">
		    <div class="box-header with-border">
		      	<h3 class="box-title">Subscription history</h3>
		    </div>
	    	<div class="box-body display-flex-wrap" style="display: flex; flex-wrap: wrap;">
	    		<div class="table-responsive" style="width:100%">
			        <table class="table table-striped">
			            <thead>
			                <tr>
			                    <th>Description.</th>
			                    <th>Trial Days</th>
			                    <th>Status</th>
			                    <th>Start Date</th>
			                </tr>
			            </thead>
			            <tbody>
			                @if($_subscription->count() > 0)
			                    @foreach($_subscription as $_sub)
			                        <tr>
			                            <td>{{ $_sub->description }}</td>
			                            <td>{{ $_sub->trial_days }}</td>
			                            <td>{{ $_sub->status }}</td>
			                            <td>{{ $_sub->start_date }}</td>
			                        </tr>
			                    @endforeach
			                @else
			                    <tr>
			                        <td colspan="5">No subscriptions yet!</td>
			                    </tr>
			                @endif
			            </tbody>
			        </table>
			    </div>
		    </div>
		</div>
    </div>
</div>

@endsection
