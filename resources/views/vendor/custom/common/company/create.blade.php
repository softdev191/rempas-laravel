@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">{{ $crud->entity_name_plural }}</span>
        <small>{{ trans('backpack::crud.add').' '.$crud->entity_name }}.</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a class="text-capitalize" href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ config('backpack.base.route_prefix') }}</a></li>
	    <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    <li class="active">{{ trans('backpack::crud.add') }}</li>
	  </ol>
	</section>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<!-- Default box -->
		@if ($crud->hasAccess('list'))
			<a href="{{ backpack_url('company') }}" class="hidden-print"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a><br><br>
		@endif
		@include('crud::inc.grouped_errors')
		  <form method="post"
		  		action="{{ backpack_url('company')}}?step=nameandaddress"
				@if ($crud->hasUploadFields('create'))
				enctype="multipart/form-data"
				@endif
		  		id="company-form">
		  {!! csrf_field() !!}
		  <div class="box">

		    <div class="box-header with-border">
		      <h3 class="box-title">{{ trans('backpack::crud.add_a_new') }} {{ $crud->entity_name }}</h3>
		    </div>
                      <div class="box-body row display-flex-wrap" style="display: flex; flex-wrap: wrap;">
		      	<!-- load the view from the application if it exists, otherwise load the one in the package -->
		      	@if(view()->exists('vendor.backpack.crud.form_content'))
			      	@include('vendor.backpack.crud.form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
			    @else
			      	@include('crud::form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
			    @endif
		      	
		    </div><!-- /.box-body -->
		    <div class="box-footer">

                @include('crud::inc.form_save_buttons')

		    </div><!-- /.box-footer-->

		  </div><!-- /.box -->
		  </form>
	</div>
</div>
@section('scripts')
<script>
    $(document).ready(function(){
        var active_tab = $("#form_tabs").find('li.active a').attr("tab_name");
        var action = "{{ backpack_url('company')}}?step="+active_tab;
        $("#company-form").attr("action",action);
    });
    $("#form_tabs li a").click(function(q)
    {
        var active_tab = $(this).attr("tab_name");
        var action = "{{ backpack_url('company')}}?step="+active_tab;
        $("#company-form").attr("action",action);
    });
    
</script>
@stop
@endsection
