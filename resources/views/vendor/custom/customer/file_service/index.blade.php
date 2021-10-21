@if(!empty($entry))
	@extends('backpack::layout')

	@section('header')
		<section class="content-header">
		  <h1>
	        <span class="text-capitalize">{{__('customer_msg.tb_header_FileService')}}</span>
	        <small>{{__('customer_msg.service_service_EditTitle')}}</small>
		  </h1>
		  <ol class="breadcrumb">
		    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
		    <li><a href="{{ url('customer/file-service') }}" class="text-capitalize">{{__('customer_msg.menu_FileServices')}}</a></li>
		    <li class="active">{{__('customer_msg.tb_header_FileService')}}:#{{ $entry->car }}</li>
		  </ol>
		</section>
	@endsection

	@section('content')

		<div class="row">
			<div class="col-md-12">
				<!-- Default box -->
				<a href="{{ url('customer/file-service') }}" class="hidden-print">
					<i class="fa fa-angle-double-left"></i> <span>{{__('customer_msg.service_BackToAllFileServices')}}</span>
				</a><br><br>
				<div class="row">
					<div class="col-md-6">
						<form method="post" action="{{ url('customer/file-service/'.$entry->id) }}" enctype="multipart/form-data">
						  	@csrf
						  	@method('PUT')
						  	<div class="box">
							    <div class="box-header with-border">
							      	<h3 class="box-title">{{__('customer_msg.service_service_EditTitle')}}</h3>
							    </div>
						    	<div class="box-body row display-flex-wrap" style="display: flex; flex-wrap: wrap;">
						    		<div class="hidden  required">
									  	<input name="user_id" value="{{ $user->id }}" class="form-control" type="hidden">
									</div>
									<div class="hidden ">
									  <input name="id" value="{{ $entry->id }}" class="form-control" type="hidden">
									</div>
									<div class="form-group col-md-6 col-xs-12 {{ $errors->has('make') ? ' has-error' : '' }}">
								    	<label>{{__('customer_msg.service_Make')}}</label>
								        <input name="make" value="{{ (old('make'))?old('make'):($entry->make?$entry->make:'') }}" placeholder="Make" class="form-control" type="text">
								        @if ($errors->has('make'))
				                            <span class="help-block">
				                                <strong>{{ $errors->first('make') }}</strong>
				                            </span>
				                        @endif
								    </div>
								    <div class="form-group col-md-6 col-xs-12 {{ $errors->has('model') ? ' has-error' : '' }}">
								    	<label>{{__('customer_msg.service_Model')}}</label>
								        <input name="model" value="{{ (old('model')) ? old('model') : ($entry->model ? $entry->model : '') }}" placeholder="Model" class="form-control" type="text">
								        @if ($errors->has('model'))
				                            <span class="help-block">
				                                <strong>{{ $errors->first('model') }}</strong>
				                            </span>
				                        @endif
								    </div>
								    <div class="form-group col-md-6 col-xs-12 {{ $errors->has('generation') ? ' has-error' : '' }}">
								    	<label>{{__('customer_msg.service_Generation')}}</label>
								        <input name="generation" value="{{ (old('generation'))?old('generation'):($entry->generation?$entry->generation:'') }}" placeholder="Generation" class="form-control" type="text">
								        @if ($errors->has('generation'))
				                            <span class="help-block">
				                                <strong>{{ $errors->first('generation') }}</strong>
				                            </span>
				                        @endif
								    </div>
								    <div class="form-group col-md-6 col-xs-12 {{ $errors->has('engine') ? ' has-error' : '' }}">
								    	<label>{{__('customer_msg.service_Engine')}}</label>
								        <input name="engine" value="{{ (old('engine'))?old('engine'):($entry->engine?$entry->engine:'') }}" placeholder="Engine" class="form-control" type="text">
								        @if ($errors->has('engine'))
				                            <span class="help-block">
				                                <strong>{{ $errors->first('engine') }}</strong>
				                            </span>
				                        @endif
								    </div>
								    <div class="form-group col-md-6 col-xs-12 {{ $errors->has('ecu') ? ' has-error' : '' }}">
								    	<label>ECU</label>
								        <input name="ecu" value="{{ (old('ecu'))?old('ecu'):($entry->ecu?$entry->ecu:'') }}" placeholder="ECU" class="form-control" type="text">
								        @if ($errors->has('ecu'))
				                            <span class="help-block">
				                                <strong>{{ $errors->first('ecu') }}</strong>
				                            </span>
				                        @endif
								    </div>

								    <div class="form-group col-md-6 col-xs-12 {{ $errors->has('engine_hp') ? ' has-error' : '' }}">
								    	<label>{{__('customer_msg.service_EngineHP')}}</label>
								        <input name="engine_hp" value="{{ (old('engine_hp')) ? old('engine_hp') : ($entry->engine_hp ? $entry->engine_hp : '') }}" placeholder="Engine HP" class="form-control" type="number">
								        @if ($errors->has('engine_hp'))
				                            <span class="help-block">
				                                <strong>{{ $errors->first('engine_hp') }}</strong>
				                            </span>
				                        @endif
								    </div>
								    <div class="form-group col-md-6 col-xs-12 {{ $errors->has('year') ? ' has-error' : '' }}">
								    	<label>{{__('customer_msg.service_YearOfManufacture')}}</label>
								    	<select class="form-control" name="year">
								    		@if(count(range(1990, date('Y'))))
								    			@foreach(range(1990, date('Y')) as $year)
								    				@php
								    					$selected = (old('year')) ? old('year') : ($entry->year ? $entry->year : '');
								    				@endphp
								    				<option value="{{ $year }}" {{ ($selected == $year)?'selected=selected':'' }}>{{ $year }}</option>
								    			@endforeach
								    		@endif
								    	</select>
								        @if ($errors->has('year'))
				                            <span class="help-block">
				                                <strong>{{ $errors->first('year') }}</strong>
				                            </span>
				                        @endif
								    </div>

								    <div class="form-group col-md-6 col-xs-12 {{ $errors->has('gearbox') ? ' has-error' : '' }}">
								    	<label>{{__('customer_msg.service_Gearbox')}}</label>
								    	<select class="form-control" name="gearbox">
								    		@if(count(config('site.file_service_gearbox')))
								    			@foreach(config('site.file_service_gearbox') as $key => $gearbox)
								    				@php
								    					$selected = (old('gearbox')) ? old('gearbox') : ($entry->gearbox ? $entry->gearbox : '');
								    				@endphp
								    				<option value="{{ $key }}" {{ ($selected == $key)?'selected=selected':'' }}>{{ $gearbox }}</option>
								    			@endforeach
								    		@endif
								    	</select>
								        @if ($errors->has('gearbox'))
				                            <span class="help-block">
				                                <strong>{{ $errors->first('gearbox') }}</strong>
				                            </span>
				                        @endif
								    </div>
									<div class="form-group col-md-6 col-xs-12 {{ $errors->has('fuel_type') ? ' has-error' : '' }}">
								    	<label>{{__('customer_msg.service_FuelType')}}</label>
								    	<select class="form-control" name="fuel_type">
								    		@if(count(config('site.file_service_fuel_type')))
								    			@foreach(config('site.file_service_fuel_type') as $key => $fuel_type)
								    				@php
								    					$selected = (old('fuel_type'))?old('fuel_type'):($entry->fuel_type?$entry->fuel_type:'');
								    				@endphp
								    				<option value="{{ $key }}" {{ ($selected == $key)?'selected=selected':'' }}>{{ $fuel_type }}</option>
								    			@endforeach
								    		@endif
								    	</select>
								        @if ($errors->has('fuel_type'))
				                            <span class="help-block">
				                                <strong>{{ $errors->first('fuel_type') }}</strong>
				                            </span>
				                        @endif
                                    </div>
                                    <div class="form-group col-md-6 col-xs-12 {{ $errors->has('fuel_type') ? ' has-error' : '' }}">
								    	<label>{{__('customer_msg.service_ReadingTool')}}</label>
								    	<select class="form-control" name="reading_tool">
								    		@if(count(config('site.file_service_reading_tool')))
								    			@foreach(config('site.file_service_reading_tool') as $key => $reading_tool)
								    				@php
                                                        $selected = (old('reading_tool')) ? old('reading_tool') : ($entry->reading_tool ? $entry->reading_tool : '');
								    				@endphp
								    				<option value="{{ $key }}" {{ ($key == $reading_tool)?'selected=selected':'' }}>{{ $reading_tool }}</option>
								    			@endforeach
								    		@endif
								    	</select>
								        @if ($errors->has('reading_tool'))
				                            <span class="help-block">
				                                <strong>{{ $errors->first('reading_tool') }}</strong>
				                            </span>
				                        @endif
								    </div>
								    <div class="form-group col-md-6 col-xs-12 {{ $errors->has('license_plate') ? ' has-error' : '' }}">
								    	<label>{{__('customer_msg.service_LicensePlate')}}</label>
								        <input name="license_plate" value="{{ (old('license_plate')) ? old('license_plate') : ($entry->license_plate ? $entry->license_plate : '') }}" placeholder="License plate" class="form-control" type="text">
								        @if ($errors->has('license_plate'))
				                            <span class="help-block">
				                                <strong>{{ $errors->first('license_plate') }}</strong>
				                            </span>
				                        @endif
								    </div>

								    <div class="form-group col-md-6 col-xs-12 {{ $errors->has('vin') ? ' has-error' : '' }}">
								    	<label>{{__('customer_msg.service_MilesKM')}}<small class="text-muted">({{__('customer_msg.service_Optional')}})</small></label>
								        <input name="vin" value="{{ (old('vin'))?old('vin'):($entry->vin?$entry->vin:'') }}" placeholder="VIN" class="form-control" type="text">
								        @if ($errors->has('vin'))
				                            <span class="help-block">
				                                <strong>{{ $errors->first('vin') }}</strong>
				                            </span>
				                        @endif
								    </div>

								    <div class="form-group col-xs-12 {{ $errors->has('note_to_engineer') ? ' has-error' : '' }}">
								    	<label>{{__('customer_msg.service_Note2engineer')}} <small class="text-muted">({{__('customer_msg.service_Optional')}})</small></label>
                                        <textarea name="note_to_engineer" placeholder="Fuel octane rating " class="form-control">{{ (old('note_to_engineer')) ? old('note_to_engineer') : ($entry->note_to_engineer ? $entry->note_to_engineer:'') }}</textarea>
								        @if ($errors->has('note_to_engineer'))
				                            <span class="help-block">
				                                <strong>{{ $errors->first('note_to_engineer') }}</strong>
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
									    <a href="{{ url('customer/file-service') }}" class="btn btn-default"><span class="fa fa-ban"></span> &nbsp;{{__('customer_msg.btn_Cancel')}}</a>
									</div>
						    	</div>
						  	</div>
						</form>
					</div>
					<div class="col-md-6">
						<div class="box">
						    <div class="box-header with-border">
						      	<h3 class="box-title">{{__('customer_msg.service_FileServiceInfo')}}</h3>
						    </div>
					    	<div class="box-body display-flex-wrap" style="display: flex; flex-wrap: wrap;">
					    		<div class="table-responsive" style="width:100%">
									<table class="table table-striped">
							            <tr>
						                    <th>No.</th>
						                    <td>{{ $entry->id }}</td>
						                </tr>
						                <tr>
						                    <th>{{__('customer_msg.service_Status')}}</th>
						                    <td>{{ $entry->status }}</td>
						                </tr>
						                <tr>
                                            <th>{{__('customer_msg.service_DateSubmitted')}}</th>
						                    <td>{{ $entry->created_at }}</td>
						                </tr>
						                <tr>
                                            <th>{{__('customer_msg.service_TuningType')}}</th>
						                    <td>{{ $entry->tuningType->label }}</td>
						                </tr>
						                <tr>
						                    <th>{{__('customer_msg.service_TuningOtions')}}</th>
						                    <td>{{ $entry->tuningTypeOptions()->pluck('label')->implode(',') }}</td>
						                </tr>
						                <tr>
                                            <th>{{__('customer_msg.service_Credits')}}</th>
						                    @php
						                    	$tuningTypeCredits = $entry->tuningType->credits;
						                    	$tuningTypeOptionsCredits = $entry->tuningTypeOptions()->sum('credits');
						                    	$credits = ($tuningTypeCredits+$tuningTypeOptionsCredits);
						                    @endphp
						                    <td>{{ number_format($credits, 2) }}</td>
						                </tr>
						                <tr>
                                            <th>{{__('customer_msg.service_OriginalFile')}}</th>
						                    <td><a href="{{ backpack_url('file-service/'.$entry->id.'/download-orginal') }}">download</a></td>
						                </tr>
						                @if((($entry->status == 'Completed') || ($entry->status == 'Waiting')) && ($entry->modified_file != ""))
							                <tr>
                                                <th>{{__('customer_msg.service_ModifiedFile')}}</th>
							                    <td>
							                    	<a href="{{ backpack_url('file-service/'.$entry->id.'/download-modified') }}">download</a>
							                    	@if($entry->status == 'Waiting')
							                    		&nbsp;&nbsp;<a href="{{ backpack_url('file-service/'.$entry->id.'/delete-modified') }}">delete</a>
							                    	@endif
							                    </td>
							                </tr>
							                <tr>
                                                <th>{{__('customer_msg.service_NoteByEngineer')}}</th>
							                	<td>{{ $entry->notes_by_engineer }}</td>
							                </tr>
						                @endif
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
	                showUpload: false,
	                showRemove: false,
	                layoutTemplates: {footer: ''},
	                overwriteInitial: false
	            });
	        })
	    </script>
	@endpush
@endif


