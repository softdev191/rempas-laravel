@extends('backpack::layout')

@section('header')
	<section class="content-header">
		<h1>
	        <span class="text-capitalize">Company information</span>
		</h1>
	  <ol class="breadcrumb">
	    <li>
	    	<a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a>
	    </li>
	    <li class="active"> Company information</li>
	  </ol>
	</section>
@endsection
@php
// $old = session()->getOldInput();
// dd(old('main_email_address') ? old('main_email_address') : $company->main_email_address);
// dd((old('main_email_address'))?old('main_email_address'):((@$company->main_email_address)?$company->main_email_address:''));
@endphp
@section('content')
	<form method="POST" action="{{ backpack_url('update-company-setting') }}" enctype="multipart/form-data">
	  	@csrf
	  	<input type="hidden" name="tab_name" value="name_and_address">
	  	<div class="hidden ">
		  	<input name="id" value="{{ @$company->id }}" class="form-control" type="hidden">
		</div>
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
			  	<div class="box">
			  		<div class="box-body display-flex-wrap" style="display: flex; flex-wrap: wrap;">
			  			<div class="nav-tabs-custom">
				            <ul class="nav nav-tabs" id="settingTabs">
				              	<li class="
				              		@if(session('tabName'))
				              			@if(session('tabName') == 'name_and_address')
				              				active
				              			@endif
				              		@else
				              			active
				              		@endif
				              	">
					              	<a href="#name_and_address" data-tab-name="name_and_address" data-toggle="tab" aria-expanded="true">
                                        {{__('customer_msg.company_NameAddr')}}
						            </a>
				              	</li>
				              	<li class="
				              		@if(session('tabName'))
				              			@if(session('tabName') == 'email_addresses')
				              				active
				              			@endif
				              		@endif
				              	">
					              	<a href="#email_addresses" data-tab-name="email_addresses" data-toggle="tab" aria-expanded="true">
                                        {{__('customer_msg.company_EmailAddr')}}
						            </a>
				              	</li>
				              	<li class="
				              		@if(session('tabName'))
				              			@if(session('tabName') == 'financial_information')
				              				active
				              			@endif
				              		@endif
				              	">
					              	<a href="#financial_information" data-tab-name="financial_information" data-toggle="tab" aria-expanded="true">
                                        {{__('customer_msg.company_Financial')}}
						            </a>
				              	</li>
				              	<li class="
				              		@if(session('tabName'))
				              			@if(session('tabName') == 'notes_to_customers')
				              				active
				              			@endif
				              		@endif
				              	">
					              	<a href="#notes_to_customers" data-tab-name="notes_to_customers" data-toggle="tab" aria-expanded="true">
                                        {{__('customer_msg.company_NotesCustomers')}}
						            </a>
				              	</li>
				              	<li class="
				              		@if(session('tabName'))
				              			@if(session('tabName') == 'smtp_setting')
				              				active
				              			@endif
				              		@endif
				              	">
					              	<a href="#smtp_setting" data-tab-name="smtp_setting" data-toggle="tab" aria-expanded="true">
                                        {{__('customer_msg.company_SMTPinfo')}}
						            </a>
				              	</li>
				              	<li class="
				              		@if(session('tabName'))
				              			@if(session('tabName') == 'paypal_setting')
				              				active
				              			@endif
				              		@endif
				              	">
					              	<a href="#paypal_setting" data-tab-name="paypal_setting" data-toggle="tab" aria-expanded="true">
                                        {{__('customer_msg.company_Paypalinfo')}}
						            </a>
                                </li>
                                <li class="
				              		@if(session('tabName'))
				              			@if(session('tabName') == 'stripe_setting')
				              				active
				              			@endif
				              		@endif
				              	">
					              	<a href="#stripe_setting" data-tab-name="stripe_setting" data-toggle="tab" aria-expanded="true">
                                        {{__('customer_msg.company_Stripeinfo')}}
						            </a>
                                </li>
                                <li class="
                                    @if(session('tabName'))
                                        @if(session('tabName') == 'evc_credits')
                                            active
                                        @endif
                                    @endif
                                    ">
                                    <a href="#evc_credits" data-tab-name="evc_credits" data-toggle="tab" aria-expanded="true">
                                    {{__('customer_msg.company_EvcCredits')}}
                                    </a>
                                </li>
                                <li class="
                                    @if(session('tabName'))
                                        @if(session('tabName') == 'Open Hours')
                                            active
                                        @endif
                                    @endif
                                    ">
                                    <a href="#open_hours" data-tab-name="open_hours" data-toggle="tab" aria-expanded="true">
                                    {{__('customer_msg.company_OpenHour')}}
                                    </a>
                                </li>
				            </ul>
				            <div class="tab-content">
					            <div class="tab-pane
					            	@if(session('tabName'))
				              			@if(session('tabName') == 'name_and_address')
				              				active
				              			@endif
				              		@else
				              			active
				              		@endif
					            " id="name_and_address">
					            	@include("vendor.custom.common.settings.setting_panel.name_and_address")
					            </div>
				              	<!-- /.tab-pane -->
				              	<div class="tab-pane
				              		@if(session('tabName'))
				              			@if(session('tabName') == 'email_addresses')
				              				active
				              			@endif
				              		@endif
				              	" id="email_addresses">
				              		@include("vendor.custom.common.settings.setting_panel.email_addresses")
				              	</div>
				              	<!-- /.tab-pane -->
				              	<div class="tab-pane
				              		@if(session('tabName'))
				              			@if(session('tabName') == 'financial_information')
				              				active
				              			@endif
				              		@endif
				              	" id="financial_information">
				              		@include("vendor.custom.common.settings.setting_panel.financial_information")
				              	</div>
				              	<!-- /.tab-pane -->
				              	<div class="tab-pane
				              		@if(session('tabName'))
				              			@if(session('tabName') == 'notes_to_customers')
				              				active
				              			@endif
				              		@endif
                                  " id="notes_to_customers">
                                  @include("vendor.custom.common.settings.setting_panel.notes_to_customers")
				              	</div>
				              	<!-- /.tab-pane -->
				              	<div class="tab-pane
				              		@if(session('tabName'))
				              			@if(session('tabName') == 'smtp_setting')
				              				active
				              			@endif
				              		@endif
                                  " id="smtp_setting">
                                  @include("vendor.custom.common.settings.setting_panel.smtp_setting")
				              	</div>
				              	<!-- /.tab-pane -->
				              	<div class="tab-pane
				              		@if(session('tabName'))
				              			@if(session('tabName') == 'paypal_setting')
				              				active
				              			@endif
				              		@endif
                                " id="paypal_setting">
									@include("vendor.custom.common.settings.setting_panel.paypal_setting")
								</div>
                                <!-- /.tab-pane -->
				              	<div class="tab-pane
									@if(session('tabName') && session('tabName') == 'stripe_setting')
											active
									@endif
                                " id="stripe_setting">
									@include('vendor.custom.common.settings.setting_panel.stripe_setting')
								</div>
								<div class="tab-pane
									@if(session('tabName') && session('tabName') == 'evc_credits')
											active
									@endif
                                " id="evc_credits">
									@include('vendor.custom.common.settings.setting_panel.evc_credits')
								</div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane
									@if(session('tabName') && session('tabName') == 'open_hours')
											active
									@endif
                                " id="open_hours">
									@include('vendor.custom.common.settings.setting_panel.open_hours')
								</div>
				            </div>
				            <!-- /.tab-content -->
				        </div>

			  		</div>
			  		<div class="box-footer">
		                <div id="saveActions" class="form-group">
						    <div class="btn-group">
						        <button type="submit" class="btn btn-danger">
						            <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
						            <span>Save</span>
						        </button>
						    </div>
						    <a href="{{ backpack_url('tuning-credit') }}" class="btn btn-default"><span class="fa fa-ban"></span> &nbsp;Cancel</a>
						</div>
			    	</div><!-- /.box-footer-->
			  	</div>

			</div>
		</div>
	</form>

@endsection
@push('after_styles')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/bower_components/bootstrap-fileinput-master/css/fileinput.min.css') }}">
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('after_scripts')
    <script src="{{ asset('vendor/adminlte/bower_components/bootstrap-fileinput-master/js/fileinput.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            @if ($company->open_check == 0)
                $('.oh-container').hide();
            @endif
            $('input[name=open_check]').on('change', function() {
                let val = $(this).is(":checked");
                if (val) {
                    $('.oh-container').show();
                } else {
                    $('.oh-container').hide();
                }
            })
            $("input[type=file]").fileinput({
                showUpload: false,
                showRemove: false,
                layoutTemplates: {footer: ''},
                overwriteInitial: true,
                @if($company->logo != null)
			        initialPreview: [
			            '{{ asset("uploads/logo/" . $company->logo) }}',
			        ],
			        initialPreviewAsData: true,
			        initialPreviewConfig: [
			        	{caption: "", url: "", key: "{{ $company->id }}"},
			        ],
		        @endif
            });


            $('#settingTabs li a').on('click', function(){
            	var element = $(this);
            	$('input[name=tab_name]').val(element.attr('data-tab-name'));
            });

            $("input[name=vat_number]").on('keyup', function(){
            	var element = $(this);
            	var vat = element.val();
            	if(vat.length > 0){
            		$('input[name=vat_percentage]').removeAttr('disabled');
            	}else{
            		$('input[name=vat_percentage]').attr('disabled', 'disabled');
            	}
            });
        });
    </script>
@endpush
