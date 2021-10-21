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
			    {{ trans('backpack::crud.edit') }}
			</li>
	  	</ol>
	</section>
@endsection

@section('content')
<style>
.direct-chat-success {
    max-width: inherit !important;
}
</style>
<div class="row">
	<div class="col-md-6 col-xs-12">
		<!-- Default box -->
		@if ($crud->hasAccess('list'))
			<a href="{{ url($crud->route) }}" class="hidden-print">
				<i class="fa fa-angle-double-left"></i>
				{{ trans('backpack::crud.back_to_all') }}
				<span>{{ $crud->entity_name_plural }}</span>
			</a>
			<br><br>
		@endif

		@include('crud::inc.grouped_errors')
        <div class="box box-success direct-chat direct-chat-success">
            <div class="box-header with-border">
              	<h3 class="box-title">{{__('customer_msg.contactus_ContactUs')}}</h3>
              	<a href="{{ backpack_url('tickets/'.$entry->id.'/mark-close') }}" class="btn btn-success pull-right">
              		{{ ($entry->is_closed == 0)? "Mark As Closed":"Closed" }}
              	</a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
	            <!-- Conversations are loaded here -->
	            <div class="direct-chat-messages" id="direct-chat-messages" style="overflow-y:scroll">
	                <!-- Message to the right -->
	                <div class="direct-chat-msg right">
	                    <div class="direct-chat-info clearfix">
	                        <span class="direct-chat-name pull-right">
	                            @if($entry->sender_id != $user->id)
	                            	@php
				                        $avtarString = $entry->sender->full_name;
				                        $avtarWords = explode(" ", $avtarString);
				                        $allFirstLetters = "";
				                        foreach ($avtarWords as $value) {
				                            $allFirstLetters .= substr($value, 0, 1);
				                        }
				                    @endphp
	                                {{ $allFirstLetters }}
	                            @else
	                                ME
	                            @endif
	                        </span>
	                        <span class="direct-chat-timestamp pull-left">
	                        	{{ $entry->created_at }}
	                        </span>
	                    </div>
	                    <!-- /.direct-chat-info -->
	                    <div class="direct-chat-text">
	                        {{ $entry->message }}
	                       	@if(!empty($entry->document))
                                <a href="{{ backpack_url('tickets/'.$entry->id.'/download-file') }}" class="ticket-file">
                                	<i class="fa fa-file"></i>
									<span class="filename">{{ $entry->document }}</span>
                                </a>
                            @endif
	                    </div>
	                    <!-- /.direct-chat-text -->
	                </div>
	                <!-- /.direct-chat-msg -->
	                @if(!empty($messages))
	                    @foreach($messages as $key=>$val)
	                        <!-- Message. Default to the left -->
	                        @if($val->sender_id != $user->id)
		                        <div class="direct-chat-msg">
		                          	<div class="direct-chat-info clearfix">
			                            <span class="direct-chat-name pull-left">
			                            	@php
						                        $avtarString = $val->sender->full_name;
						                        $avtarWords = explode(" ", $avtarString);
						                        $allFirstLetters = "";
						                        foreach ($avtarWords as $value) {
						                            $allFirstLetters .= substr($value, 0, 1);
						                        }
						                    @endphp
			                                {{ $allFirstLetters }}
			                            </span>
			                            <span class="direct-chat-timestamp pull-right">
			                            	{{ $val->created_at }}
			                            </span>
		                          	</div>
		                          <!-- /.direct-chat-info -->

		                          <div class="direct-chat-text">
		                            @if(!empty($val->message))
		                            	{{ $val->message }}
		                            @endif
		                            @if(!empty($val->document))
		                                <a href="{{ backpack_url('tickets/'.$val->id.'/download-file') }}" class="ticket-file">
		                                	<i class="fa fa-file"></i>
											<span class="filename">{{ $val->document }}</span>
		                                </a>
		                            @endif
		                          </div>
		                          <!-- /.direct-chat-text -->
		                        </div>
	                        @else
	                        	<div class="direct-chat-msg right">
		                            <div class="direct-chat-info clearfix">
		                              	<span class="direct-chat-name pull-right">Me</span>
		                              	<span class="direct-chat-timestamp pull-left">
		                              		{{ $val->created_at }}
		                              	</span>
		                            </div>
		                            <!-- /.direct-chat-info -->
		                            <div class="direct-chat-text">
		                              	{{ $val->message }}
		                              	@if(!empty($val->document))
		                                	<a href="{{ backpack_url('tickets/'.$val->id.'/download-file') }}" class="ticket-file">
		                                		<i class="fa fa-file"></i>
												<span class="filename">{{ $val->document }}</span>
		                                	</a>
		                              	@endif
		                            </div>
		                            <!-- /.direct-chat-text -->
	                          	</div>
	                        @endif
	                        <!-- /.direct-chat-msg -->
	                    @endforeach
	                @endif
	            </div>
	            <!--/.direct-chat-messages-->
	            <!-- Contacts are loaded here -->
	            <div class="direct-chat-contacts">
	                <ul class="contacts-list">
	                  <li>
	                    <a href="javascript:;">
	                      	<div class="contacts-list-info">
	                            <span class="contacts-list-name">
	                              	Count Dracula
	                              	<small class="contacts-list-date pull-right">2/28/2015</small>
	                            </span>
	                        	<span class="contacts-list-msg">How have you been? I was...</span>
	                      	</div>
	                      	<!-- /.contacts-list-info -->
	                    </a>
	                  </li>
	                  <!-- End Contact Item -->
	                </ul>
	                <!-- /.contatcts-list -->
	            </div>
	            <!-- /.direct-chat-pane -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              	<form method="post" action="{{ url($crud->route.'/'.$entry->getKey()) }}">
	                {!! csrf_field() !!}
	                {!! method_field('PUT') !!}
	                <div class="form-group col-xs-12">
	                	<textarea name="message" placeholder="{{__('customer_msg.title_TypeMessage')}} ..." class="form-control" cols="50" rows="4">{{ old('message') }}</textarea>
	                </div>
	                <div class="hidden">
		              	<input type="hidden" name="file_servcie_id" value="{{ ($fileService) ? $fileService->id: 0 }}">
		              </div>
	                <div class="hidden ">
					  	<input name="uploaded_file" value="" class="form-control" type="hidden">
					</div>
	                <div class="form-group col-xs-12">
	                	<div class="input-group">
	                		<input type="file" name="document"/>
		                   	<span class="input-group-btn">
		                        <button type="submit" class="btn btn-success">{{__('customer_msg.btn_Send')}}</button>
		                   	</span>
	                	</div>
	                </div>
              	</form>

            </div>
            <!-- /.box-footer-->
        </div>


		<!----changes---->
		@if($entry->file_servcie_id > 0)
			<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">{{__('customer_msg.contactus_CustomerInfor')}}</h3>
					</div>
					<div class="box-body display-flex-wrap" style="display: flex; flex-wrap: wrap;">
						<div class="table-responsive" style="width:100%">
							<table class="table table-striped">
								<tr>
									<th>{{__('customer_msg.contactus_Business')}}</th>

									<td>{{ $entry->FileService->user->business_name }}</td>
								</tr>
								<tr>
									<th>{{__('customer_msg.contactus_Name')}}</th>
									<td>{{ $entry->FileService->user->full_name }}</td>
								</tr>
								<tr>
									<th>{{__('customer_msg.contactus_EmailAddress')}}</th>
									<td>{{ $entry->FileService->user->email }}</td>
								</tr>
								<tr>
									<th>{{__('customer_msg.contactus_Phone')}}</th>
									<td>{{ $entry->FileService->user->phone }}</td>
								</tr>
								<tr>
									<th>{{__('customer_msg.contactus_Country')}}</th>
									<td>{{ $entry->FileService->user->county }}</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
		@endif
	</div>
	<div class="col-md-6 col-xs-12">
		@if(!empty($fileService))
			<div class="clearfix">&nbsp;</div>
			<div class="clearfix">&nbsp;</div>
			<div class="row">
				<div class="col-md-12">
					<div class="box">
				    	<div class="box-header with-border">
				      		<h3 class="box-title">{{__('customer_msg.service_FileServiceInfo')}}</h3>
				    	</div>
			    		<div class="box-body display-flex-wrap" style="display: flex; flex-wrap: wrap;">
			    			<div class="table-responsive" style="width:100%">
								<table class="table table-striped">
						            <tr>
					                    <th>No.</th>
					                    <td>{{ $fileService->displayable_id }}</td>
					                </tr>
					                <tr>
					                    <th>{{__('customer_msg.service_Status')}}</th>
					                    <td>{{ $fileService->status }}</td>
					                </tr>
					                <tr>
					                    <th>{{__('customer_msg.service_DateSubmitted')}}</th>
					                    <td>{{ $fileService->created_at }}</td>
					                </tr>
					                <tr>
					                    <th>{{__('customer_msg.service_TuningType')}}</th>
					                    <td>{{ $fileService->tuningType->label }}</td>
					                </tr>
					                <tr>
					                    <th>{{__('customer_msg.service_TuningOtions')}}</th>
					                    <td>{{ $fileService->tuningTypeOptions()->pluck('label')->implode(',') }}</td>
					                </tr>
					                <tr>
					                    <th>{{__('customer_msg.service_Credits')}}</th>
					                    @php
					                    	$tuningTypeCredits = $fileService->tuningType->credits;
					                    	$tuningTypeOptionsCredits = $fileService->tuningTypeOptions()->sum('credits');
					                    	$credits = ($tuningTypeCredits+$tuningTypeOptionsCredits);
					                    @endphp
					                    <td>{{ number_format($credits, 2) }}</td>
					                </tr>
					                <tr>
					                    <th>{{__('customer_msg.service_OriginalFile')}}</th>
					                    <td>
					                    	<a href="{{ backpack_url('file-service/'.$fileService->id.'/download-orginal') }}">
						                    	download
						                    </a>
					                    </td>
					                </tr>
					                @if((($fileService->status == 'Completed') || ($fileService->status == 'Waiting')) && ($fileService->modified_file != ""))
						                <tr>
						                    <th>{{__('customer_msg.service_ModifiedFile')}}</th>
						                    <td>
						                    	<a href="{{ backpack_url('file-service/'.$fileService->id.'/download-modified') }}">download</a>
						                    	@if($fileService->status == 'Waiting')
						                    		&nbsp;&nbsp;<a href="{{ backpack_url('file-service/'.$fileService->id.'/delete-modified') }}">delete</a>
						                    	@endif
						                    </td>
						                </tr>
					            	@endif
					    		</table>
							</div>
			    		</div>
					</div>
				</div>
		        <div class="col-md-12">
				  	<div class="box">
					    <div class="box-header with-border">
					      	<h3 class="box-title">{{__('customer_msg.contactus_CarInformation')}}</h3>
					    </div>
				    	<div class="box-body display-flex-wrap" style="display: flex; flex-wrap: wrap;">
				    		<div class="table-responsive" style="width:100%">
								<table class="table table-striped">
						            <tr>
					                    <th>{{__('customer_msg.tb_header_Car')}}</th>
					                    <td>{{ $fileService->car }}</td>
					                </tr>
					                <tr>
					                    <th>{{__('customer_msg.service_Engine')}}</th>
					                    <td>{{ $fileService->engine }}</td>
					                </tr>
					                <tr>
					                    <th>ECU</th>
					                    <td>{{ $fileService->ecu }}</td>
					                </tr>
					                <tr>
					                    <th>{{__('customer_msg.service_EngineHP')}}</th>
					                    <td>{{ $fileService->engine_hp }}</td>
					                </tr>
					                <tr>
					                    <th>{{__('customer_msg.contactus_Year')}}</th>
					                    <td>{{ $fileService->year }}</td>
					                </tr>
					                <tr>
					                    <th>{{__('customer_msg.service_Gearbox')}}</th>
					                    <td>{{ $entry->gearbox }}</td>
					                </tr>
					                <tr>
					                    <th>{{__('customer_msg.tb_header_License')}}</th>
					                    <td>{{ $fileService->license_plate }}</td>
					                </tr>
					                <tr>
					                    <th>VIN</th>
					                    <td>{{ $fileService->vin }}</td>
					                </tr>
					                <tr>
					                    <th>{{__('customer_msg.service_Note2engineer')}}</th>
					                    <td>{{ $fileService->note_to_engineer }}</td>
					                </tr>
						        </table>
						    </div>
				    	</div>
				  	</div>
				</div>
			</div>
		@endif
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
                showPreview: false,
                showCancel: false,
                layoutTemplates: {footer: ''},
		    }).on('load', function(event) {
		    	$('.fileinput-upload-button').hide();
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
		   document.getElementById('direct-chat-messages').scrollTop = document.getElementById('direct-chat-messages').scrollHeight;
		});
	</script>
@stop
@endsection
