@extends('backpack::auth.layout')

@section('content')
	<style>
		.row{margin-top:10px;}
		.box-default{
			background-color: #f5f5f5;
			border-left:#ababab;
		}
		.login-col{
			max-width:1000px;
		}
		.regForm{
			border-left:1px solid ;
			border-color: rgb(171, 171, 171)!important;
		}
		.loginBtn{
			color:#e21b23;
			font-weight: 700;
			white-space: nowrap;
			text-decoration:underline;
		}
		.loginBtnDiv{
			margin-top:10px;
		}
	</style>
		<script src='https://www.google.com/recaptcha/api.js'></script>
	<div class="row">
        <div class="login-col">
            <div class="box box-default">
				<div class="row">
					<div class="col-sm-4">
						<div class="box-header with-border">
							@if(\File::exists(public_path('uploads/logo/' . $company->logo)))
								<div class="logo-admin">
									<img src="{{ asset('uploads/logo/' . $company->logo) }}" width="340px">
								</div>
							@endif
						</div>
						<div class="box-header loginBtnDiv">
							<center>
								<a class="loginBtn" href="{{ route('login') }}">
									{{ __('Login instead ') }}
								</a>
							</center>
						</div>
					</div>
					<div class="col-sm-8 regForm">

						<div class="box-body">
							<div class="box-title login-title">{{ __('Register') }}</div>
							@if(session('status') && session('message'))
								@if(session('status') == 'success')
									<div class="alert alert-success">
										<p>{{ session('message') }}</p>
									</div>
								@else
									<div class="alert alert-danger">
										<p>{{ session('message') }}</p>
									</div>
								@endif
							@endif


							{{ Form::open(array('url' => 'user-register')) }}
								@csrf

								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-6">
											<div class="form-group{{ $errors->has('lang') ? ' has-error' : '' }}">
												<label class="control-label">{{ __('Language') }}</label>

												@php
													$lang = [
                                                        'en'=> 'English',
                                                        'fr'=> 'French',
                                                        'es'=> 'Spanish',
                                                        'pt'=> 'Portuguese',
                                                        'it'=> 'Italian',
                                                        'ja'=> 'Japanese',
                                                        'nl'=> 'Dutch',
                                                        'pl'=> 'Polish',
                                                        'de'=> 'German',
                                                        'ru'=> 'Russian',
                                                        'tr'=> 'Turkish'
                                                    ];
												@endphp
												{!! Form::select('lang', $lang, null ,['class' => 'form-control']) !!}
												@if ($errors->has('lang'))
													<span class="help-block">
														<strong>{{ $errors->first('lang') }}</strong>
													</span>
												@endif
											</div>
										</div>
									</div>
								</div>


								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-6">
											<div class="form-group">
												<label class="control-label">{{ __('PERSONAL INFO') }} </label>
											</div>
										</div>
									</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-sm-4">
											<div class="form-group{{ $errors->has('private') ? ' has-error' : '' }}">
												<label class="control-label">{{ __('customer_msg.contactInfo_Type') }}</label>

												@php
													$titles  = ['0'=> 'Private Customer','1'=>'Business Customer'];
												@endphp
												{!! Form::select('private', $titles, 0 ,['class' => 'form-control customer-type']) !!}
												@if ($errors->has('private'))
													<span class="help-block">
														<strong>{{ $errors->first('private') }}</strong>
													</span>
												@endif
											</div>
                                        </div>
                                        <div class="col-sm-4 col-tax" style="display: none">
											<div class="form-group{{ $errors->has('vat_number') ? ' has-error' : '' }}">
												<label class="control-label">{{ __('customer_msg.contactInfo_taxvat') }}</label>
												{!! Form::text('vat_number',null,['class' => 'form-control']) !!}

												@if ($errors->has('vat_number'))
													<span class="help-block">
														<strong>{{ $errors->first('vat_number') }}</strong>
													</span>
												@endif
											</div>
										</div>
                                    </div>
                                </div>
								<div class="row">

									<div class="col-sm-12">

										<div class="col-sm-4">
											<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
												<label class="control-label">{{ __('Title') }}</label>

												@php
													$titles  = ['Mr'=> 'Mr','Ms'=>'Ms'];
												@endphp
												{!! Form::select('title', $titles, null ,['class' => 'form-control']) !!}
												@if ($errors->has('title'))
													<span class="help-block">
														<strong>{{ $errors->first('title') }}</strong>
													</span>
												@endif
											</div>
										</div>

										<div class="col-sm-4">
											<div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
												<label class="control-label">{{ __('First name') }}</label>
												{!! Form::text('first_name',null,['class' => 'form-control']) !!}

												@if ($errors->has('first_name'))
													<span class="help-block">
														<strong>{{ $errors->first('first_name') }}</strong>
													</span>
												@endif
											</div>
										</div>

										<div class="col-sm-4">
											<div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
												<label class="control-label">{{ __('Last name') }}</label>
												{!! Form::text('last_name',null,['class' => 'form-control']) !!}

												@if ($errors->has('last_name'))
													<span class="help-block">
														<strong>{{ $errors->first('last_name') }}</strong>
													</span>
												@endif
											</div>
										</div>

									</div>
								</div>


								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-6">
											<div class="form-group">
												<label class="control-label">{{ __('LOCATION INFO') }} </label>
											</div>
										</div>
									</div>
								</div>


								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-6">
											<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
												<label class="control-label">{{ __('Email') }}</label>
												{!! Form::email('email',null,['class' => 'form-control']) !!}

												@if ($errors->has('last_name'))
													<span class="help-block">
														<strong>{{ $errors->first('email') }}</strong>
													</span>
												@endif
											</div>
										</div>

										<div class="col-sm-6">
											<div class="form-group{{ $errors->has('address_line_1') ? ' has-error' : '' }}">
												<label class="control-label">{{ __('Address line 1') }}</label>
												{!! Form::text('address_line_1',null,['class' => 'form-control']) !!}
												@if ($errors->has('address_line_1'))
													<span class="help-block">
														<strong>{{ $errors->first('address_line_1') }}</strong>
													</span>
												@endif
											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12">

										<div class="col-sm-6">
											<div class="form-group">
												<label class="control-label">{{ __('Address line 2') }}</label>
												{!! Form::text('address_line_2',null,['class' => 'form-control']) !!}
											</div>
										</div>

										<div class="col-sm-6">
											<div class="form-group{{ $errors->has('town') ? ' has-error' : '' }}">
												<label class="control-label">{{ __('Town') }}</label>
												{!! Form::text('town',null,['class' => 'form-control']) !!}
												@if ($errors->has('town'))
													<span class="help-block">
														<strong>{{ $errors->first('town') }}</strong>
													</span>
												@endif
											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12">

										<div class="col-sm-6">
											<div class="form-group ">
												<label class="control-label">{{ __('Post Code') }}</label>
												{!! Form::text('post_code',null,['class' => 'form-control']) !!}
											</div>
										</div>

										<div class="col-sm-6">
											<div class="form-group{{ $errors->has('county') ? ' has-error' : '' }}">
												<label class="control-label">{{ __('Country') }}</label>
												{!! Form::text('county',null,['class' => 'form-control']) !!}
												@if ($errors->has('county'))
													<span class="help-block">
														<strong>{{ $errors->first('county') }}</strong>
													</span>
												@endif
											</div>
										</div>
									</div>
								</div>



								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-6">
											<div class="form-group">
												<label class="control-label">{{ __('ADDITIONAL INFO') }} </label>
											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12">

										<div class="col-sm-6">
											<div class="form-group{{ $errors->has('business_name') ? ' has-error' : '' }}">
												<label class="control-label">{{ __('Business Name') }}</label>
												{!! Form::text('business_name',null,['class' => 'form-control']) !!}
												@if ($errors->has('business_name'))
													<span class="help-block">
														<strong>{{ $errors->first('business_name') }}</strong>
													</span>
												@endif
											</div>
										</div>

										<div class="col-sm-6">
											<div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
												<label class="control-label">{{ __('Phone') }}</label>
												{!! Form::text('phone',null,['class' => 'form-control']) !!}
												@if ($errors->has('phone'))
													<span class="help-block">
														<strong>{{ $errors->first('phone') }}</strong>
													</span>
												@endif
											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-6">
											<div class="form-group">
												<label class="control-label">{{ __('Tools') }}</label>
												{!! Form::textarea('tools',null,['class' => 'form-control']) !!}
											</div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
												<label class="control-label">{{ __('Password') }}</label>
												{!! Form::text('password',null,['class' => 'form-control']) !!}
												@if ($errors->has('password'))
													<span class="help-block">
														<strong>{{ $errors->first('password') }}</strong>
													</span>
												@endif
                                            </div>
                                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
												<label class="control-label">Confirm Password</label>
												{!! Form::text('password_confirmation',null,['class' => 'form-control']) !!}
												@if ($errors->has('password_confirmation'))
													<span class="help-block">
														<strong>{{ $errors->first('password_confirmation') }}</strong>
													</span>
												@endif
											</div>
                                        </div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-6">
											<div class="form-group">
												<div class="g-recaptcha" data-sitekey="{{env('SITE_KEY')}}" ></div>
											</div>
										</div>
									</div>

								</div>
								<div class="row ">
									<div class="col-sm-12">
										<div class="col-sm-8">
											<div class="form-group has-error">
												<div id="error"></div>
											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											<div class="col-sm-4">
												<button type="submit" id="btnSubmit" class="btn btn-danger">
													{{ __('Register') }}
												</button>
											</div>
										</div>
									</div>
								</div>
							{{ Form::close() }}
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>

@endsection


@section('after_scripts')
	<script>
		$("#btnSubmit").click(function () {

			var response = grecaptcha.getResponse();
			html ='';
			$("#error").hide();
			$("#error").html(html);
			if(response.length != 0){ //validation successful

			}else{

				var html = '<span class="help-block">';
						html += '<strong>Invalid captcha code. Please try again.</strong>';
					html += '</span>';
				$("#error").show();
				$("#error").html(html);
				return false;
			}

		});
        $('.customer-type').change(function() {
            var customer_type = $(this).val();
            if (customer_type == 1) {
                $('.col-tax').show();
            } else {
                $('.col-tax').hide();
            }
        })
	</script>
@endsection
