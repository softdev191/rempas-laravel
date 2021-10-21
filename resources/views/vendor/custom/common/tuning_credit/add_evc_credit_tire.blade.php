@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">Add EVC credit tier</span>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ backpack_url('tuning-credit') }}" class="text-capitalize">Tuning credits</a></li>
	    <li class="active">Credit tier</li>
	  </ol>
	</section>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<!-- Default box -->
		<a href="{{ backpack_url('tuning-evc-credit') }}" class="hidden-print">
			<i class="fa fa-angle-double-left"></i> Back to all  <span>tuning credit prices</span>
		</a><br><br>

		<form method="POST" action="{{ backpack_url('tuning-evc-credit/credit-tire') }}">
		  	@csrf
		  	<div class="box">
			    <div class="box-header with-border">
			      	<h3 class="box-title">Add EVC credit tier</h3>
			    </div>
		    	<div class="box-body row display-flex-wrap" style="display: flex; flex-wrap: wrap;">
					<div class="form-group col-md-4 col-xs-12 required {{ $errors->has('amount') ? ' has-error' : '' }}">
					    <label>Amount</label>
				        <input name="amount" value="" placeholder="Amount" class="form-control" type="text">
				        @if ($errors->has('amount'))
                            <span class="help-block">
                                <strong>{{ $errors->first('amount') }}</strong>
                            </span>
                        @endif
				    </div>
		    	</div><!-- /.box-body -->
			    <div class="box-footer">
	                <div id="saveActions" class="form-group">
					    <div class="btn-group">
					        <button type="submit" class="btn btn-danger">
					            <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
					            <span>Save</span>
					        </button>
					    </div>
					    <a href="{{ backpack_url('tuning-evc-credit') }}" class="btn btn-default"><span class="fa fa-ban"></span> &nbsp;Cancel</a>
					</div>
		    	</div><!-- /.box-footer-->
		  	</div><!-- /.box -->
		</form>
	</div>
</div>

@endsection
