<div class="row">
    @if ($openStatus == 2)
    <div class="col-lg-12">
        <div class="callout callout-warning">
            <h4>{{ $company->name }}</h4>
            <p>We are currently closed for file services. Please take note of our opening hours in your customer Dashboard.</p>
            <p>When we open you will be able to upload your file for processing.</p>
            <p>Thank you.</p>
        </div>
    </div>
    @endif
    <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{{ $openFileServices }}</h3>
                <p>{{__('customer_msg.dash_OpenFileService')}}</p>
            </div>
            <div class="icon">
                <i class="fa fa-copy"></i>
            </div>
            <a href="{{ url('customer/file-service?status=O') }}" class="small-box-footer">
                {{__('customer_msg.a_moreInfo')}} <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>{{ $waitingFileServices }}</h3>
                <p>{{__('customer_msg.dash_WaitingService')}}</p>
            </div>
            <div class="icon">
                <i class="fa fa-copy"></i>
            </div>
            <a href="{{ url('customer/file-service?status=W') }}" class="small-box-footer">
                {{__('customer_msg.a_moreInfo')}} <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{{ $complatedFileServices }}</h3>
                <p>{{__('customer_msg.dash_CompletedService')}}</p>
            </div>
            <div class="icon">
                <i class="fa fa-copy"></i>
            </div>
            <a href="{{ url('customer/file-service?status=C') }}" class="small-box-footer">
                {{__('customer_msg.a_moreInfo')}} <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <h3 class="box-title">{{__('customer_msg.dash_RecentService')}}</h3>
        <div class="table-responsive" style="width:100%">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>{{__('customer_msg.tb_header_Car')}}</th>
                        <th>{{__('customer_msg.tb_header_Created')}}</th>
                        <th>{{__('customer_msg.tb_header_Status')}}</th>
                        <th>{{__('customer_msg.tb_header_Otions')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @if($fileServices->count() > 0)
                        @foreach($fileServices as $fileService)
                            <tr>
                                <td>{{ $fileService->displayable_id }}</td>
                                <td>{{ $fileService->car }}</td>
                                <td>{{ $fileService->created_at }}</td>
                                <td>{{ $fileService->status }}</td>
                                <td>
                                    @if($fileService->status == 'Completed')
                                        <a href="{{ url('customer/file-service/'.$fileService->id.'/download-modified') }}" title="Download modified file">
                                            <i class="fa fa-btn fa-download"></i>
                                        </a>
                                    @endif
                                    &nbsp;&nbsp;
                                    <a href="{{ url('customer/file-service/'.$fileService->id.'/download-orginal') }}" title="Download orginal file">
                                        <i class="fa fa-btn fa-download"></i>
                                    </a>

                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">No file services created by you yet!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <a href="{{ url('customer/file-service') }}" class="btn btn-danger">{{__('customer_msg.btn_ViewAllFileServices')}} <i class="fa fa-arrow-right"></i></a>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="row">
                <div class="col-md-6">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{__('customer_msg.dash_CompanyInformation')}}</h3>
                    </div>
                    <div class="box-body display-flex-wrap">
                        {{ $company->name }}
                        <br>{{ $company->address_line_1 }}
                    </div>
                    <div class="box-header with-border">
                        <h3 class="box-title">{{__('customer_msg.dash_Financial')}}</h3>
                    </div>
                    <div class="box-body display-flex-wrap">
                        {{__('customer_msg.dash_VATNumber')}}:  {{ $company->vat_number }}

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{__('customer_msg.dash_EmailAddresses')}}</h3>
                    </div>
                    <div class="box-body display-flex-wrap">
                        <table class="table table-striped">
                            <tr>
                                <th>{{__('customer_msg.dash_Main')}}</th>
                                <td><a href="mailto:{{ $company->main_email_address }}">{{ $company->main_email_address }}</a></td>
                            </tr>
                            <tr>
                                <th>{{__('customer_msg.dash_Support')}}</th>
                                <td><a href="mailto:{{ $company->support_email_address }}">{{ $company->support_email_address }}</a></td>
                            </tr>
                            <tr>
                                <th>{{__('customer_msg.dash_Billing')}}</th>
                                <td><a href="mailto:{{ $company->billing_email_address }}">{{ $company->billing_email_address }}</a></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

			<div class="row">
				<div class="col-md-12">
					<div class="box">
						<div class="row">
							<div class="col-md-6">
								<div class="box-header with-border">
									<h3 class="box-title">{{__('customer_msg.dash_GiveRating')}}</h3>
									<br/> <b> ( {{__('customer_msg.dash_OverallCompanyRating')}} : {{$company->rating}} )</b>
								</div>
								<div class="box-body display-flex-wrap">

										{{ Form::open(array('url' => 'customer/add-rating')) }}
										@php
											//$disabled ='';

											if(isset($customerRating->rating)){
												//$disabled = 'disabled="disabled"';
												$ratings = $customerRating->rating;
											}else{
												$ratings = $company->rating;
											}
										@endphp
										<div class="form-group">
											@if(isset($customerRating->rating))
												<label>You gave  Rating</label>
												{{Form::hidden('id', $customerRating->id, ['class' => 'form-control'])}}
											@endif
											{{Form::select('rating', ['1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5], $ratings, ['class' => 'form-control'])}}
										</div>

										<div class="form-group">
											{{ Form::submit('Submit',['class'=>'btn btn-success']) }}
										</div>

										{{ Form::close() }}


								</div>
                            </div>
                            @if($company->reseller_id)
                            <div class="col-md-6">
                                <div class="box-body display-flex-wrap">
                                    {{ Form::open(array('url' => 'customer/set-reseller')) }}
                                    <div class="form-group {{ $errors->has('reseller_id') ? ' has-error' : '' }}">
                                        <span>Entering your EVC account number here activate reseller with us</span><br>

                                        <div style='display: flex'>
                                            <div>
                                                <label style='margin-top: 10px'>ID</label>
                                                <div style='display: flex;'>
                                                    <input style='width: 100px; margin-top:5px' name="reseller_id" value="{{ $resellerId }}" placeholder="Reseller ID" class="form-control" type="text">
                                                    @if ($errors->has('reseller_id'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('reseller_id') }}</strong>
                                                        </span>
                                                    @endif
                                                    @if ($resellerId)
                                                        <i class="fa fa-check" aria-hidden="true" style='margin-left: 10px; margin-top: 8px ;font-size: 20px; color: green'></i>
                                                    @endif
                                                </div>
                                            </div>
                                            <div style='margin-left: 20px;'>
                                                <div style='margin-top: 10px'>
                                                    @if ($resellerId)
                                                        <label>EVC Credits</label>
                                                        <h3 style="margin:5px 0px; font-size:3rem; font-weight:bold">{{ $evcCount }}</h3>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="form-group">
                                        {{ Form::submit('Submit',['class'=>'btn btn-success']) }}
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                            @endif
						</div>
					</div>
				</div>
			</div>

            <div class="row">
                <div class="col-md-12">
                    <div class="box-header with-border">
                        <h3 class="box-title">Notes</h3>
                    </div>
                    <div class="box-body display-flex-wrap">
                        <p>{{ $company->customer_note }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
