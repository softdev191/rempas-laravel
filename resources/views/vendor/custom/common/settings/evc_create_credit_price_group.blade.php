@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">Add price group</span>
        <small>Add credit price groups.</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ backpack_url('tuning-credit') }}" class="text-capitalize">Tuning credit prices</a></li>
	    <li class="active">Add</li>
	  </ol>
	</section>
@endsection

@section('content')
	<form method="post" action="{{ url($crud->route) }}">
	  	@csrf
		<div class="row">
			<div class="col-md-12">
				@if($errors->any())
				    <div class="callout callout-danger">
				        <h4>Please fix the errors</h4>
				        <ul>
				            @foreach($errors->all() as $error)
				                <li>{{ $error }}</li>
				            @endforeach
				        </ul>
				    </div>
				@endif
				<!-- Default box -->
				<a href="{{ backpack_url('tuning-evc-credit') }}" class="hidden-print">
					<i class="fa fa-angle-double-left"></i> Back to all  <span>tuning credit prices</span>
				</a><br><br>
			  	<div class="box">
				    <div class="box-header with-border">
				      	<h3 class="box-title">{{__('customer_msg.price_TranHistory')}}</h3>
				    </div>
			    	<div class="box-body row display-flex-wrap" style="display: flex; flex-wrap: wrap;">
			    		<div class="col-md-6 col-xs-12">
			    			<div class="form-group col-xs-12 required {{ $errors->has('name') ? ' has-error' : '' }}">
							    <label>{{__('customer_msg.price_GroupName')}}</label>
						        <input type="text" name="name" class="form-control" placeholder="Group name" value="{{ old('name') }}">
						        @if ($errors->has('name'))
		                            <span class="help-block">
		                                <strong>{{ $errors->first('name') }}</strong>
		                            </span>
		                        @endif
						    </div>
						    @php
						    	$tuningCreditTires = \App\Models\TuningCreditTire::where('company_id', $user->company_id)->where('group_type', 'evc')->orderBy('amount', 'ASC')->get();
						    @endphp
						    @if($tuningCreditTires->count() > 0)
						    	@foreach($tuningCreditTires as $tuningCreditTire)
						    		<div class="form-group col-md-6 col-xs-12 required {{ $errors->has('credit_tires.'.$tuningCreditTire->id.'.from_credit') ? ' has-error' : '' }}">
									    <label>{{ $tuningCreditTire->amount }} {{__('customer_msg.price_CreditFrom')}}</label>
								        <input type="text" name="credit_tires[{{ $tuningCreditTire->id }}][from_credit]" class="form-control" placeholder="" value="{{ old('credit_tires.'.$tuningCreditTire->id.'.from_credit') }}">
								        @if ($errors->has('credit_tires.'.$tuningCreditTire->id.'.from_credit'))
				                            <span class="help-block">
				                                <strong>{{ $errors->first('credit_tires.'.$tuningCreditTire->id.'.from_credit') }}</strong>
				                            </span>
				                        @endif
								    </div>
								    <div class="form-group col-md-6 col-xs-12 required {{ $errors->has('credit_tires.'.$tuningCreditTire->id.'.for_credit') ? ' has-error' : '' }}">
									    <label>{{ $tuningCreditTire->amount }} {{__('customer_msg.price_CreditFor')}}</label>
								        <input type="text" name="credit_tires[{{ $tuningCreditTire->id }}][for_credit]" class="form-control" placeholder="" value="{{ old('credit_tires.'.$tuningCreditTire->id.'.for_credit') }}">
								        @if ($errors->has('credit_tires.'.$tuningCreditTire->id.'.for_credit'))
				                            <span class="help-block">
				                                <strong>{{ $errors->first('credit_tires.'.$tuningCreditTire->id.'.for_credit') }}</strong>
				                            </span>
				                        @endif
								    </div>
						    	@endforeach
						    @endif
			    		</div>
			    	</div><!-- /.box-body -->
				    <div class="box-footer">
		                <div id="saveActions" class="form-group">
						    <div class="btn-group">
						        <button type="submit" class="btn btn-success">
						            <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
						            <span>{{__('customer_msg.btn_Save')}}</span>
						        </button>
						    </div>
						    <a href="{{ backpack_url('tuning-evc-credit') }}" class="btn btn-default"><span class="fa fa-ban"></span> &nbsp;{{__('customer_msg.btn_Cancel')}}</a>
						</div>
			    	</div>
			  	</div>
			</div>
		</div>
	</form>
@endsection

