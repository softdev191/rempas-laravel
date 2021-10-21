@if(!empty($entry))
	@extends('backpack::layout')

	@section('header')
		<section class="content-header">
		  <h1>
	        <span class="text-capitalize">orders</span>
	        <small>Edit order.</small>
		  </h1>
		  <ol class="breadcrumb">
		    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
		    <li><a href="{{ backpack_url('order') }}" class="text-capitalize">Order</a></li>
		    <li class="active">order:#{{ $entry->id }}</li>
		  </ol>
		</section>
	@endsection

	@section('content')
	<div class="row">
		<div class="col-md-12">
			<!-- Default box -->
			<a href="{{ backpack_url('order') }}" class="hidden-print">
				<i class="fa fa-angle-double-left"></i> Back to all  <span>orders</span>
			</a><br><br>
			<div class="row">
				<div class="col-md-6">
					<form method="post" action="{{ backpack_url('order/'.$entry->id) }}">
					  	@csrf
					  	@method('PUT')
					  	<div class="box">
						    <div class="box-header with-border">
						      	<h3 class="box-title">Update order status</h3>
						    </div>
					    	<div class="box-body row display-flex-wrap" style="display: flex; flex-wrap: wrap;">
					    		<div class="hidden ">
								  	<input name="id" value="{{ $entry->id }}" class="form-control" type="hidden">
								</div>
								<div class="form-group col-xs-12 required {{ $errors->has('status') ? ' has-error' : '' }}">
								    <label>Status</label>
							        <select name="status" class="form-control">
							        	@if(config('site.order_status'))
							        		@foreach(config('site.order_status') as $ck=>$cv)
								        		<option value="{{ $cv }}" {{ ($entry->status == $cv)?'selected=selected':'' }}>{{ $cv }}</option>
								        	@endforeach
							        	@endif
							        </select>
							        @if ($errors->has('status'))
			                            <span class="help-block">
			                                <strong>{{ $errors->first('status') }}</strong>
			                            </span>
			                        @endif
							    </div>
					    	</div><!-- /.box-body -->
						    <div class="box-footer">
				                <div id="saveActions" class="form-group">
								    <div class="btn-group">
								        <button type="submit" class="btn btn-success">
								            <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
								            <span>Save</span>
								        </button>
								    </div>
								</div>
					    	</div>
					  	</div>
					</form>
				</div>
				<div class="col-md-6">
					<div class="box">
					    <div class="box-header with-border">
					      	<h3 class="box-title">Order information</h3>
					    </div>
				    	<div class="box-body display-flex-wrap" style="display: flex; flex-wrap: wrap;">
				    		<div class="table-responsive" style="width:100%">
								<table class="table table-striped">
						            <tr>
					                    <th>Customer</th>
					                    <td>{{ $entry->user->full_name }}</td>
					                </tr>
					                <tr>
					                    <th>Order description</th>
					                    <td>{{ $entry->description }}</td>
					                </tr>
					                <tr>
					                    <th>Order reference</th>
					                    <td>{{ $entry->id }}</td>
					                </tr>
					                <tr>
					                    <th>Subtotal</th>
					                    <td>{{ config('site.currency_sign') }}{{ number_format($entry->amount, 2) }}</td>
					                </tr>
					                <tr>
					                    <th>Total</th>
					                    <td>{{ config('site.currency_sign') }}{{ number_format($entry->amount, 2) }}</td>
					                </tr>
					                <tr>
					                    <th>Order status</th>
					                    <td>{{ $entry->status }}</td>
					                </tr>
					                <tr>
					                    <th>Invoice</th>
					                    <td><a href="{{ backpack_url('order/'.$entry->id.'/invoice') }}">download</a></td>
					                </tr>

						        </table>
						    </div>
				    	</div>
				  	</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="box">
						    <div class="box-header with-border">
						      	<h3 class="box-title">Items in this order</h3>
						    </div>
					    	<div class="box-body display-flex-wrap" style="display: flex; flex-wrap: wrap;">
					    		<div class="table-responsive" style="width:100%">
									<table class="table table-striped">
							            <thead>
							                <tr>
							                    <th>Description</th>
							                    <th>Price</th>
							                    <th>Quantity</th>
							                    <th>Amount</th>
							                </tr>
							            </thead>
							            <tbody>
							                <tr>
					                            <td>{{ $entry->description }}</td>
					                            <td>{{ config('site.currency_sign') }} {{ $entry->amount }}</td>
					                            <td>1</td>
					                            <td>{{ config('site.currency_sign') }} {{ $entry->amount }}</td>
					                        </tr>
							            </tbody>
							        </table>
							    </div>
					    	</div>
					  	</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>

	@endsection
@endif

