@extends('backpack::layout')
@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">Contact Us</span>
	  </h1>
	  <ol class="breadcrumb">
	       <li>
            <a class="text-capitalize" href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">
              {{ config('backpack.base.route_prefix') }}
            </a>
        </li>
	     <li>
          <a href="{{ url($crud->route) }}" class="text-capitalize">
            {{ $crud->entity_name_plural }}
          </a>
        </li>
	      <li class="active">
            {{ trans('backpack::crud.add') }}
        </li>
	  </ol>
	</section>
@endsection

@section('content')
<div class="row">
  	<div class="col-md-6 col-xs-12">
  		<!-- Default box -->
  		@if ($crud->hasAccess('list'))
  			 <a href="{{ url($crud->route) }}" class="hidden-print">
              <i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} 
              <span>{{ $crud->entity_name_plural }}</span>
          </a>
          <br><br>
  		@endif

  		@include('crud::inc.grouped_errors')
      <div class="box">
          <div class="box-body row display-flex-wrap">
            <form method="post" action="{{ url($crud->route) }}" >
                {!! csrf_field() !!}
              <div class="form-group col-md-12 subject {{ $errors->has('subject') ? ' has-error' : '' }}">
                  <label>Subject</label>
                  <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Subject" class="form-control"/>
                  @if ($errors->has('subject'))
                      <span class="help-block">
                          <strong>{{ $errors->first('subject') }}</strong>
                      </span>
                  @endif
              </div>
              <div class="hidden">
              	<input type="hidden" name="file_servcie_id" value="0">
              </div>
              <div class="form-group col-md-12 required {{ $errors->has('message') ? ' has-error' : '' }}">
                  <label>Message</label>
                  <textarea name="message" placeholder="Type Message ..." class="form-control" cols="50" rows="4">{{ old('message') }}</textarea>
                  @if ($errors->has('message'))
                      <span class="help-block">
                          <strong>{{ $errors->first('message') }}</strong>
                      </span>
                  @endif
              </div>
              <div class="hidden ">
                  <input name="uploaded_file" value="" class="form-control" type="hidden">
              </div>
              <div class="form-group col-md-12">
                  <label>File</label>
                  <input type="file" name="document" />
              </div>
              <div class="form-group col-md-12">
                  <label>Mark as closed</label>
                  <input type="checkbox" name="is_closed" value="1" />
              </div>
              <div class="form-group col-md-12">
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-danger">Send</button>
                 </span>
              </div>
            </form>
          </div>
          <!-- /.box-footer-->
        </div>     
  	</div>
</div>
@section('scripts')
  <script src="{{ asset('vendor/adminlte/bower_components/bootstrap-fileinput-master/js/fileinput.min.js') }}"></script>
  <script>
      $(document).ready(function(){
        $("input[type=file]").fileinput({
          	uploadUrl: "{{ backpack_url('upload-ticket-file') }}",
          	uploadAsync: true,
            showRemove: false,
            showCancel: false,
            showPreview: false,
            showUploadedThumbs: false,
            //showUpload: false,
            layoutTemplates: {footer: ''},
        }).on('change', function(event) {
          	 $('.fileinput-upload-button').hide();
              $('.fileinput-upload-button').click();
        }).on('fileuploaded', function(event, data) {
          	if(data.response.status === true){
          		  $("input[name=uploaded_file]").val(data.response.file);
          		  $('#saveActions .btn.btn-danger').attr('enabled', 'enabled');
          	}else{
          		  $('#saveActions .btn.btn-danger').attr('disabled', 'disabled');
          		  $('.kv-upload-progress . progress').html('<div class="progress-bar bg-success progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%;">Error</div>');
          	}
        });
    });
  </script>
@stop
@endsection
