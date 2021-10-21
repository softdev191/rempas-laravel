@if(!empty($entry))
	@extends('backpack::layout')

	@section('header')
		<section class="content-header">
		  <h1>
	        <span class="text-capitalize">File service</span>
	        <small>Edit file service.</small>
		  </h1>
		  <ol class="breadcrumb">
		    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
		    <li><a href="{{ backpack_url('file-service') }}" class="text-capitalize">File services1</a></li>
		    <li class="active">File service:#{{ $entry->car }}</li>
		  </ol>
		</section>
	@endsection

	@section('content')
		<div class="row">
			<div class="col-md-12">
				<!-- Default box -->
				<a href="{{ backpack_url('file-service') }}" class="hidden-print">
					<i class="fa fa-angle-double-left"></i> Back to all  <span>file services</span>
				</a><br><br>
				<div class="row">
					<div class="col-md-6">
						<form method="post" action="{{ backpack_url('file-service/'.$entry->id) }}" enctype="multipart/form-data">
						  	@csrf
						  	@method('PUT')
						  	<div class="box">
							    <div class="box-header with-border">
							      	<h3 class="box-title">Process the file service</h3>
							    </div>
						    	<div class="box-body row display-flex-wrap" style="display: flex; flex-wrap: wrap;">
						    		<div class="hidden ">
									  	<input name="id" value="{{ $entry->id }}" class="form-control" type="hidden">
									</div>
									<div class="hidden ">
									  	<input name="uploaded_file" value="" class="form-control" type="hidden">
									</div>
									<div class="form-group col-xs-12 required {{ $errors->has('status') ? ' has-error' : '' }}">
									    <label>Status</label>
								        <select name="status" class="form-control">
								        	@if(config('site.file_service_staus'))
								        		@foreach(config('site.file_service_staus') as $ck=>$cv)
									        		<option value="{{ $ck }}" {{ ($entry->status == $cv)?'selected=selected':'' }}>{{ $cv }}</option>
									        	@endforeach
								        	@endif
								        </select>
								        @if ($errors->has('status'))
				                            <span class="help-block">
				                                <strong>{{ $errors->first('status') }}</strong>
				                            </span>
				                        @endif
								    </div>
								    <div class="form-group col-xs-12 required {{ $errors->has('file') ? ' has-error' : '' }}">
									    <label>Modified file</label>
								        <input type="file" name="file">
								        {{--
								        @if(($entry->status == 'Completed') && ($entry->modified_file != ""))
								        	<a href="{{ backpack_url('file-service/'.$entry->id.'/download-modified') }}">View current uploaded file</a>
								        @endif
								        --}}
								        @if ($errors->has('file'))
				                            <span class="help-block">
				                                <strong>{{ $errors->first('file') }}</strong>
				                            </span>
				                        @endif
								    </div>
								    <div class="form-group col-xs-12 required {{ $errors->has('notes_by_engineer') ? ' has-error' : '' }}">
									    <label>Notes by engineer <small class="text-muted">(optional)</small></label>
								        <textarea name="notes_by_engineer" placeholder="Notes by engineer" class="form-control">{{ (old('notes_by_engineer'))?old('notes_by_engineer'):($entry->notes_by_engineer)?$entry->notes_by_engineer:'' }}</textarea>
								        @if($errors->has('notes_by_engineer'))
				                            <span class="help-block">
				                                <strong>{{ $errors->first('notes_by_engineer') }}</strong>
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
									</div>
						    	</div>
						  	</div>
						</form>
						<div class="box">
						    <div class="box-header with-border">
						      	<h3 class="box-title">Customer information</h3>
						    </div>
					    	<div class="box-body display-flex-wrap" style="display: flex; flex-wrap: wrap;">
					    		<div class="table-responsive" style="width:100%">
									<table class="table table-striped">
							            <tr>
						                    <th>Business</th>
						                    <td>{{ $entry->user->business_name }}</td>
						                </tr>
						                <tr>
						                    <th>Name</th>
						                    <td>{{ $entry->user->full_name }}</td>
						                </tr>
						                <tr>
						                    <th>Email address</th>
						                    <td>{{ $entry->user->email }}</td>
						                </tr>
						                <tr>
						                    <th>Phone</th>
						                    <td>{{ $entry->user->phone }}</td>
						                </tr>
						                <tr>
						                    <th>County</th>
						                    <td>{{ $entry->user->county }}</td>
						                </tr>
							        </table>
							    </div>
					    	</div>
					  	</div>
					</div>
					<div class="col-md-6">
						<div class="box">
						    <div class="box-header with-border">
						      	<h3 class="box-title">File service information</h3>
						    </div>
					    	<div class="box-body display-flex-wrap" style="display: flex; flex-wrap: wrap;">
					    		<div class="table-responsive" style="width:100%">
									<table class="table table-striped">
							            <tr>
						                    <th>No.</th>
						                    <td>{{ $entry->displayable_id }}</td>
						                </tr>
						                <tr>
						                    <th>Status</th>
						                    <td>{{ $entry->status }}</td>
						                </tr>
						                <tr>
						                    <th>Date submitted</th>
						                    <td>{{ $entry->created_at }}</td>
						                </tr>
						                <tr>
						                    <th>Tuning type</th>
						                    <td>{{ $entry->tuningType->label }}</td>
						                </tr>
						                <tr>
						                    <th>Tuning options</th>
						                    <td>{{ $entry->tuningTypeOptions()->pluck('label')->implode(',') }}</td>
						                </tr>
						                <tr>
						                    <th>Credits</th>
						                    @php
						                    	$tuningTypeCredits = $entry->tuningType->credits;

						                    	$tuningTypeOptionsCredits = $entry->tuningTypeOptions()->sum('credits');
												$credits = ($tuningTypeCredits+$tuningTypeOptionsCredits);
						                    @endphp
						                    <td>{{ number_format($credits, 2) }}</td>
						                </tr>
						                <tr>
						                    <th>Original file</th>
						                    <td><a href="{{ backpack_url('file-service/'.$entry->id.'/download-orginal') }}">download</a></td>
						                </tr>
						                @if((($entry->status == 'Completed') || ($entry->status == 'Waiting')) && ($entry->modified_file != ""))
							                <tr>
							                    <th>Modified file</th>
							                    <td>
							                    	<a href="{{ backpack_url('file-service/'.$entry->id.'/download-modified') }}">download</a>
							                    	@if($entry->status == 'Waiting')
							                    		&nbsp;&nbsp;<a href="{{ backpack_url('file-service/'.$entry->id.'/delete-modified') }}">delete</a>
							                    	@endif
							                    </td>
							                </tr>
						                @endif
							        </table>
							    </div>
					    	</div>
					  	</div>

					  	<div class="box">
						    <div class="box-header with-border">
						      	<h3 class="box-title">Car information</h3>
						    </div>
					    	<div class="box-body display-flex-wrap" style="display: flex; flex-wrap: wrap;">
					    		<div class="table-responsive" style="width:100%">
									<table class="table table-striped">
							            <tr>
						                    <th>Car</th>
						                    <td>{{ $entry->car }}</td>
						                </tr>
						                <tr>
						                    <th>Engine</th>
						                    <td>{{ $entry->engine }}</td>
						                </tr>
						                <tr>
						                    <th>ECU</th>
						                    <td>{{ $entry->ecu }}</td>
						                </tr>
						                <tr>
						                    <th>Engine HP</th>
						                    <td>{{ $entry->engine_hp }}</td>
						                </tr>
						                <tr>
						                    <th>Year of Manufacture</th>
						                    <td>{{ $entry->year }}</td>
						                </tr>
						                <tr>
						                    <th>Gearbox</th>
						                    <td>{{ config('site.file_service_gearbox')[$entry->gearbox] }}</td>
						                </tr>
										<tr>
						                    <th>Fuel Type</th>
						                    <td>{{ ($entry->fuel_type)?config('site.file_service_fuel_type')[$entry->fuel_type]:'' }}</td>
                                        </tr>
                                        <tr>
						                    <th>Reading Tool</th>
						                    <td>{{ ($entry->reading_tool)?config('site.file_service_reading_tool')[$entry->reading_tool]:'' }}</td>
						                </tr>
						                <tr>
						                    <th>License plate</th>
						                    <td>{{ $entry->license_plate }}</td>
						                </tr>
						                <tr>
						                    <th>Miles / KM</th>
						                    <td>{{ $entry->vin }}</td>
						                </tr>
						                <tr>
						                    <th>Note to engineer</th>
						                    <td>{{ $entry->note_to_engineer }}</td>
						                </tr>
							        </table>
							    </div>
					    	</div>
					  	</div>
					</div>
				</div>

			</div>
		</div>
	@endsection
	@push('after_styles')
	    <link rel="stylesheet" href="{{ asset('vendor/adminlte/bower_components/bootstrap-fileinput-master/css/fileinput.min.css') }}">
	@endpush

	{{-- FIELD JS - will be loaded in the after_scripts section --}}
	@push('after_scripts')
	    <script src="{{ asset('vendor/adminlte/bower_components/bootstrap-fileinput-master/js/fileinput.min.js') }}"></script>
	    <script>
	        $(document).ready(function(){
	            $("input[type=file]").fileinput({
	            	uploadUrl: "{{ backpack_url('upload-file-service-file') }}",
	            	uploadAsync: true,
	                showRemove: false,
	                showPreview: false,
	                layoutTemplates: {footer: ''},
	            }).on('change', function(event) {
	            	$('.fileinput-upload-button').hide();
				    $('.fileinput-upload-button').click();
				}).on('fileuploaderror', function(event, data, msg) {
					$("input[name=uploaded_file]").val('');
					$('#saveActions .btn.btn-danger').removeAttr('enabled');
					$('#saveActions .btn.btn-danger').attr('disabled', 'disabled');
					new PNotify({
					  title: "Error",
					  text: "Unable to upload. File shouldn\'t be greater than 10 MB. Please select another file.",
					  type: "error"
					});
				}).on('fileuploaded', function(event, data) {
                    if(data.response.status == true){
                        $("input[name=uploaded_file]").val(data.response.file);
						$('#saveActions .btn.btn-danger').removeAttr('disabled');
                        $('#saveActions .btn.btn-danger').attr('enabled', 'enabled');
                    }else{
						$('#saveActions .btn.btn-danger').removeAttr('enabled');
                        $('#saveActions .btn.btn-danger').attr('disabled', 'disabled');
						new PNotify({
						  title: "Error",
						  text: "Unable to upload. File shouldn\'t be greater than 10 MB. Please select another file.",
						  type: "error"
						});
                    }
                });
	        });
	    </script>
	@endpush
@endif


