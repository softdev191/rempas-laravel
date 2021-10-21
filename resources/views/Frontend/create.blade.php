@extends('layouts.appnew')

@section('content')

	<script src='https://www.google.com/recaptcha/api.js'></script>	
    <div class="container">
        <div class="register-col">
            <div class="box box-default">
                
				@if ($message = Session::get('success'))

					<div class="alert alert-success alert-block">
						<button type="button" class="close" data-dismiss="alert">×</button>	
							<strong>{{ $message }}</strong>
					</div>

				@endif
				
				@if ($message = Session::get('error'))

				<div class="alert alert-danger alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>	
						<strong>{{ $message }}</strong>
				</div>

				@endif
				
				 @if($errors->any())

				<div class="alert alert-danger alert-block">
					 @foreach ($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach 
				</div>

				@endif

                <div class="box-body">
                   <a  class="view-btn back-btn" href="/">Back</a>
					{!! Form::open(array('action' => 'Frontend\CompanyController@postPaymentWithpaypal', 'autocomplete' => "off")) !!}
						
						
						<div class="form-group">
						  {!! Form::label('name', 'Company Name') !!}
						  {!! Form::text('name', '', ['class' => 'form-control', 'placeholder'=>'Company Name']) !!}
						</div>
						
						<div class="form-group">
						  {!! Form::label('main_email_address', 'Email Address') !!}
						  {!! Form::text('main_email_address', '', ['class' => 'form-control', 'placeholder'=>'Email Address']) !!}
						</div>
						
						<div class="form-group">
						  {!! Form::label('password', 'Password') !!}
						  {!! Form::password('password', ['class' => 'form-control', 'placeholder'=>'Password']) !!}
						</div>
						
						<div class="form-group">
						  {!! Form::label('password_confirmation', 'Confirm Password') !!}
						  {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder'=>'Confirm Password']) !!}
						</div>

						@if($_GET['domain'] == 'regular')
						<div class="form-group">
						  {!! Form::label('domain_prefix', 'Choose your preferred subdomain (yourname.myremaps.com)') !!}
						  <div class="d-flex d-flex-custom">
						  {!! Form::text('domain_prefix', '', ['class' => 'form-control', 'id' => 'domain_prefix', 'placeholder'=>'Eg: yourname']) !!}
						  {!! Form::text('domain_suffix', '.myremaps.com', ['class' => 'form-control', 'readonly' => 'true']) !!}
						  </div>
						</div>
						@endif
						@if($_GET['domain'] == 'own')
						<div class="form-group">
							{!! Form::label('own_domain', 'Choose your own domain') !!}
							{!! Form::text('own_domain', '', ['class' => 'form-control', 'id' => 'own_domain', 'placeholder'=>'Full FQDN inc https://']) !!}
							{!! Form::label('domain', 'This allows you to have the portal service appear on your own domain name. for example. https://yourdomain.com (or) subdomain,') !!}
							{!! Form::label('own_domain', 'example https://portal.yourdomain.com, if you already have a company website a subdomain is the best option.') !!}
							{!! Form::label('own_domain', 'In order for this to work our support team will ask for you to make some changes to your DNS records
							to be pointed to our servers') !!}
						</div>
						@endif

						<div class="form-group">
						  {!! Form::label('address_line_1', 'Address line 1') !!}
						  {!! Form::text('address_line_1', '', ['class' => 'form-control', 'placeholder'=>'Address line 1']) !!}
						</div>
						
						<div class="form-group">
						  {!! Form::label('address_line_2', 'Address line 2') !!}
						  {!! Form::text('address_line_2', '', ['class' => 'form-control', 'placeholder'=>'Address line 2']) !!}
						</div>
						
						<div class="form-group">
						  {!! Form::label('town', 'Town') !!}
						  {!! Form::text('town', '', ['class' => 'form-control', 'placeholder'=>'Town']) !!}
						</div>
						
						<div class="form-group">
						  {!! Form::label('country', 'Country') !!}
						  {!! Form::text('country', '', ['class' => 'form-control', 'placeholder'=>'Country']) !!}
						</div>						
						
						<div class="form-group">
						  {!! Form::label('vat_number', 'VAT Number (optional)') !!}
						  {!! Form::text('vat_number', '', ['class' => 'form-control', 'placeholder'=>'VAT Number']) !!}
						</div>	
						
						<div class="form-group">
							@php /*
								{!! Form::hidden('package_id', $packageID) !!}
								{!! Form::hidden('amount', $packageAmount) !!}
							*/ @endphp
							{!! Form::hidden('domain_link', '', ['id' => 'domain_link']) !!}
						</div>
						
						<div class="form-group">
							<div class="g-recaptcha" data-sitekey="{{env('SITE_KEY')}}" ></div>	
						</div>
						
						<div class="form-group">
							<div id="error"></div>
						</div>
						<button id="btnSubmit" class="btn btn-success view-btn" type="submit">Submit</button>
					
						
					{!! Form::close() !!}

                   
                </div>
            </div>
			<p>If you need any help, please contact to <a class="custom-link" href="mailto:support@myremaps.com">support@myremaps.com</a></p>
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
				return true;
			}else{
				
				var html = '<span class="alert alert-danger">';
						html += 'Invalid captcha code. Please try again.';
					html += '</span>';
				$("#error").show();
				$("#error").html(html);
				return false;
			}
			
		});
	</script>
@endsection
