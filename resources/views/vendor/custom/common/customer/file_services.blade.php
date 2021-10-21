@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">File services </span>
        <small>File services for {{ $customer->full_name }}</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ backpack_url('customer') }}" class="text-capitalize">Customers</a></li>
	    <li class="active">File services</li>
	  </ol>
	</section>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<!-- Default box -->
		<a href="{{ backpack_url('customer') }}" class="hidden-print">
			<i class="fa fa-angle-double-left"></i> Back to all  <span>customers</span>
		</a><br><br>

		<div class="box">
		    <div class="box-header with-border">
		      	<h3 class="box-title">File services</h3>
		    </div>
	    	<div class="box-body display-flex-wrap" style="display: flex; flex-wrap: wrap;">
	    		<div class="table-responsive" style="width:100%">
			        <table class="table table-striped">
			            <thead>
			                <tr>
			                    <th>No.</th>
			                    <th>Car</th>
			                    <th>ECU</th>
			                    <th>Created</th>
			                    <th>Options</th>
			                </tr>
			            </thead>
			            <tbody>
			                @if($fileServices->count() > 0)
			                    @foreach($fileServices as $fileService)
			                        <tr>
			                            <td>{{ $fileService->id }}</td>
			                            <td>{{ $fileService->car }}</td>
			                            <td>{{ $fileService->ecu }}</td>
			                            <td>{{ $fileService->created_at }}</td>
			                            <td>
			                            	<a href="{{ url('admin/file-service/'.$fileService->id.'/edit') }}" class="btn btn-xs btn-default"><i class="fa fa-edit"></i></a>
			                            	&nbsp;&nbsp;
			                            	<a href="{{ url('admin/customer/file-service/'.$fileService->id.'/delete') }}" class="btn btn-xs btn-default"><i class="fa fa-trash"></i></a>
			                            </td>
			                        </tr>
			                    @endforeach
			                @else
			                    <tr>
			                        <td colspan="5">No file services yet!</td>
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
