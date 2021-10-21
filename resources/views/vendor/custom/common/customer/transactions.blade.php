@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">Customer transactions</span>
        <small>Transactions for {{ $customer->full_name }}</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ backpack_url('customer') }}" class="text-capitalize">Customers</a></li>
	    <li class="active">Transactions</li>
	  </ol>
	</section>
@endsection

@section('content')
<div class="row">
	<div class="col-md-6">
		<!-- Default box -->
		<a href="{{ backpack_url('customer') }}" class="hidden-print">
			<i class="fa fa-angle-double-left"></i> Back to all  <span>customers</span>
		</a><br><br>

		<form method="POST" action="{{ backpack_url('customer/transaction') }}">
		  	@csrf
		  	<div class="box">
			    <div class="box-header with-border">
			      	<h3 class="box-title">{{__('customer_msg.tran_Add')}}</h3>
			    </div>
		    	<div class="box-body row display-flex-wrap" style="display: flex; flex-wrap: wrap;">
		    		<input type="hidden" name="user_id" value="{{ $customer->id }}">
					<div class="form-group col-md-6 col-xs-12 required {{ $errors->has('description') ? ' has-error' : '' }}">
					    <label>{{__('customer_msg.tran_Desc')}}</label>
				        <input name="description" value="" placeholder="Description" class="form-control" type="text">
				        @if ($errors->has('description'))
                            <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
				    </div>
				    <div style="width: 100%"></div>
				    <div class="form-group col-md-6 col-xs-12 required {{ $errors->has('credits') ? ' has-error' : '' }}">
					    <label>{{__('customer_msg.tran_Credits')}}</label>
				        <input name="credits" placeholder="Credits" class="form-control" type="text">
				        @if ($errors->has('credits'))
                            <span class="help-block">
                                <strong>{{ $errors->first('credits') }}</strong>
                            </span>
                        @endif
				    </div>
				    <div style="width: 100%"></div>
				    <div class="form-group col-md-6 col-xs-12 required {{ $errors->has('type') ? ' has-error' : '' }}">
					    <label>{{__('customer_msg.tran_Give')}}</label>
				        <select name="type" class="form-control">
				        	<option value="">Select an option</option>
				        	<option value="A">Give (+)</option>
				        	<option value="S">Take (-)</option>
				        </select>
				        @if ($errors->has('type'))
                            <span class="help-block">
                                <strong>{{ $errors->first('type') }}</strong>
                            </span>
                        @endif
				    </div>
		    	</div><!-- /.box-body -->
			    <div class="box-footer">
	                <div id="saveActions" class="form-group">
					    <div class="btn-group">
					        <button type="submit" class="btn btn-danger">
					            <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
					            <span>{{__('customer_msg.btn_Save')}}</span>
					        </button>
					    </div>
					    <a href="{{ backpack_url('customer') }}" class="btn btn-default"><span class="fa fa-ban"></span> &nbsp;{{__('customer_msg.btn_Cancel')}}</a>
					</div>
		    	</div><!-- /.box-footer-->
		  	</div><!-- /.box -->
		</form>
    </div>
    @if($company->reseller_id && $customer->reseller_id)
    <div class="col-md-6">
		<!-- Default box -->
		<a href="#" class="hidden-print" style='color: transparent'>
			EVC Transaction Header
		</a><br><br>

		<form method="POST" action="{{ backpack_url('customer/transaction-evc') }}">
		  	@csrf
		  	<div class="box">
			    <div class="box-header with-border">
			      	<h3 class="box-title">{{__('customer_msg.tran_Add')}} (<b>EVC</b> Credit)</h3>
			    </div>
		    	<div class="box-body row display-flex-wrap" style="display: flex; flex-wrap: wrap;">
		    		<input type="hidden" name="user_id" value="{{ $customer->id }}">
					<div class="form-group col-md-6 col-xs-12 required {{ $errors->has('description') ? ' has-error' : '' }}">
					    <label>{{__('customer_msg.tran_Desc')}}</label>
				        <input name="description" value="" placeholder="Description" class="form-control" type="text">
				        @if ($errors->has('description'))
                            <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
				    </div>
				    <div style="width: 100%"></div>
				    <div class="form-group col-md-6 col-xs-12 required {{ $errors->has('credits') ? ' has-error' : '' }}">
					    <label>{{__('customer_msg.tran_Credits')}}</label>
				        <input name="credits" placeholder="Credits" class="form-control" type="text">
				        @if ($errors->has('credits'))
                            <span class="help-block">
                                <strong>{{ $errors->first('credits') }}</strong>
                            </span>
                        @endif
				    </div>
				    <div style="width: 100%"></div>
				    <div class="form-group col-md-6 col-xs-12 required {{ $errors->has('type') ? ' has-error' : '' }}">
					    <label>{{__('customer_msg.tran_Give')}}</label>
				        <select name="type" class="form-control">
				        	<option value="">Select an option</option>
				        	<option value="A">Give (+)</option>
				        	<option value="S">Take (-)</option>
				        </select>
				        @if ($errors->has('type'))
                            <span class="help-block">
                                <strong>{{ $errors->first('type') }}</strong>
                            </span>
                        @endif
				    </div>
		    	</div><!-- /.box-body -->
			    <div class="box-footer">
	                <div id="saveActions" class="form-group">
					    <div class="btn-group">
					        <button type="submit" class="btn btn-danger">
					            <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
					            <span>{{__('customer_msg.btn_Save')}}</span>
					        </button>
					    </div>
					    <a href="{{ backpack_url('customer') }}" class="btn btn-default"><span class="fa fa-ban"></span> &nbsp;{{__('customer_msg.btn_Cancel')}}</a>
					</div>
		    	</div><!-- /.box-footer-->
		  	</div><!-- /.box -->
		</form>
    </div>
    @endif
</div>

<div class="row">
    <div class="col-md-12">
    	<div class="box">
		    <div class="box-header with-border">
		      	<h3 class="box-title">{{__('customer_msg.tran_TranHistory')}}</h3>
		    </div>
	    	<div class="box-body display-flex-wrap" style="display: flex; flex-wrap: wrap;">
	    		<div class="table-responsive" style="width:100%">
			        <table class="table table-striped">
			            <thead>
			                <tr>
			                    <th>{{__('customer_msg.tb_header_Description')}}.</th>
                                <th>{{__('customer_msg.tb_header_Credits')}}.</th>
                                <th>{{__('customer_msg.tb_header_Status')}}.</th>
                                <th>{{__('customer_msg.tb_header_Date')}}.</th>
                                <th>{{__('customer_msg.tb_header_Options')}}.</th>
			                </tr>
			            </thead>
			            <tbody>
			                @if($transactions->count() > 0)
			                    @foreach($transactions as $transaction)
			                        <tr>
			                            <td>{{ $transaction->description }}</td>
			                            <td>{{ $transaction->credits_with_type }}</td>
			                            <td>{{ $transaction->status }}</td>
			                            <td>{{ $transaction->created_at }}</td>
			                            <td>
			                            	<a href="{{ backpack_url('customer/transaction/'.$transaction->id.'/delete') }}" class="btn btn-xs btn-default"><i class="fa fa-trash"></i> Delete</a>
			                            </td>
			                        </tr>
			                    @endforeach
			                @else
			                    <tr>
			                        <td colspan="5">No transactions yet!</td>
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
