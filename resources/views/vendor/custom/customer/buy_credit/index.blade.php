@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">{{__('customer_msg.menu_BuyTuningCredits')}}</span>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li class="active">{{__('customer_msg.menu_BuyTuningCredits')}}</li>
	  </ol>
	</section>
@endsection

@section('content')
@php
	$isPayAble = FALSE;
	$isVatCalculation = FALSE;
	if(($company->vat_number != null) && ($company->vat_percentage != null) && ($user->add_tax)){
		$isVatCalculation = TRUE;
	}
@endphp
<style>
    .payment-image {
        width: 190px;
        height: 70px;
        padding: 10px;
    }
    .payment-active {
        border: 2px solid grey;
        border-radius: 8px;
    }
    .panel-title {
        display: inline;
        font-weight: bold;
    }
    .display-table {
        display: table;
    }
    .display-tr {
        display: table-row;
    }
    .display-td {
        display: table-cell;
        vertical-align: middle;
        width: 61%;
    }
</style>
<div class="row">
	<div class="col-md-12">
		  	<div class="box">
                @if($tuningCreditGroup || $tuningEVCCreditGroup)
                    <div class="box-body row display-flex-wrap" style="display: flex; flex-wrap: wrap;">
                        @if($tuningCreditGroup)
			    		<div class="col-md-6 table-responsive">
                            <h2 style='color: white'>
                                <span class="text-capitalize">Original</span>
                            </h2>
                            <form id="mainForm" method="POST" action="{{ route('pay.with.paypal') }}">
                                @csrf
                                @if($tuningCreditGroup)
                                <input type="hidden" name="tuning_credit_group_id" value="{{ $tuningCreditGroup->id }}">
                                @endif
                                @if($tuningEVCCreditGroup)
                                <input type="hidden" name="tuning_evc_credit_group_id" value="{{ $tuningEVCCreditGroup->id }}">
                                @endif
                                <input type="hidden" name="credit_type" value="">
                                <input type="hidden" name="vat_number" value="{{ $company->vat_number }}">
                                <input type="hidden" name="vat_percentage" value="{{ ($company->vat_number != null && $user->add_tax)?$company->vat_percentage:'' }}">
                                <input type="hidden" name="item_name" value="Tuning credit">
                                <input type="hidden" name="item_description" value="">
                                <input type="hidden" name="item_amount" value="">
                                <input type="hidden" name="total_amount" value="">
                                <input type="hidden" name="item_tax" value="">
                                <input type="hidden" name="item_tax_percentage" value="{{ ($company->vat_number != null && $user->add_tax)?$company->vat_percentage:'' }}">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>{{__('customer_msg.tb_header_Description')}}</th>
                                            <th>{{__('customer_msg.tb_header_From')}}</th>
                                            <th>{{__('customer_msg.tb_header_For')}}</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($groupCreditTires->count() > 0)
                                            @foreach($groupCreditTires as $groupCreditTire)
                                                <tr>
                                                    <td>
                                                        <input type="radio" name="item_credits" value="{{ $groupCreditTire->amount }}" {{ ($loop->first)?'checked="checked"':'' }} data-item-amount="{{ $groupCreditTire->pivot->for_credit }}" data-item-description="purchase {{ $groupCreditTire->amount }} tuning credit">
                                                    </td>
                                                    <td>{{ $groupCreditTire->amount }} credits</td>
                                                    <td>
                                                        {{ config('site.currency_sign') }}
                                                        {{
                                                            number_format($groupCreditTire->pivot->from_credit, 2)
                                                        }}
                                                    </td>
                                                    <td>
                                                        {{ config('site.currency_sign') }}
                                                        {{
                                                            number_format($groupCreditTire->pivot->for_credit, 2)
                                                        }}
                                                    </td>
                                                    <td>
                                                        @if($groupCreditTire->pivot->from_credit > $groupCreditTire->pivot->for_credit)
                                                            Save {{ config('site.currency_sign').' '.number_format(($groupCreditTire->pivot->from_credit - $groupCreditTire->pivot->for_credit), 2) }}
                                                        @endif
                                                    </td>
                                                </tr>
                                                @php
                                                    $isPayAble = TRUE;
                                                @endphp
                                            @endforeach
                                        @endif
                                        @if($isVatCalculation == TRUE)
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>VAT (<span class="vat_percentage">0</span>)%</td>
                                                <td>&nbsp;</td>
                                                <td>
                                                    {{ config('site.currency_sign') }}
                                                    <span class="vat_amount">0.00</span>
                                                </td>
                                            </tr>
                                        @endif

                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>{{__('customer_msg.tb_header_OrderTotal')}}</th>
                                            <th>&nbsp;</th>
                                            <th>
                                                {{ config('site.currency_sign') }}
                                                <span class="payable-amount"></span>
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>

				    		<h4>{{__('customer_msg.buytuning_PaymentMethod')}}</h4>
				    		<div class="form-group">
				    			<img id="payment_img_paypal" src="{{ asset('images/logo-paypal.png') }}" class="payment-image payment-active" onclick="selectPayment('paypal')">
                            </div>
                            @if ($user->company->stripe_key)
                            <div class="form-group">
				    			<img id="payment_img_stripe" src="{{ asset('images/logo-stripe.png') }}" class="payment-image" onclick="selectPayment('stripe')">
                            </div>
                            @endif
                            <div id="stripe-details" class="row" style="display: none">
                                <div class="col-md-12">
                                    <div class="panel panel-default credit-card-box">
                                        <div class="panel-heading" >
                                            <div class="row display-tr" >
                                                <h3 class="panel-title" >Payment Details</h3>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            @if (Session::has('success'))
                                                <div class="alert alert-success text-center">
                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                                    <p>{{ Session::get('success') }}</p>
                                                </div>
                                            @endif
                                            <form id="stripe_form" role="form" action="{{ route('pay.with.stripe') }}" method="post" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="{{ $stripeKey }}" id="payment-form">
                                                @csrf
                                                @if($tuningCreditGroup)
                                                <input type="hidden" name="stripe_tuning_credit_group_id" value="{{ $tuningCreditGroup->id }}">
                                                @endif
                                                @if($tuningEVCCreditGroup)
                                                <input type="hidden" name="stripe_tuning_evc_credit_group_id" value="{{ $tuningEVCCreditGroup->id }}">
                                                @endif
                                                <input type="hidden" name="stripe_credit_type" value="">
                                                <input type="hidden" name="stripe_item_credits" value="">
                                                <input type="hidden" name="stripe_vat_number" value="{{ $company->vat_number }}">
                                                <input type="hidden" name="stripe_vat_percentage" value="{{ ($company->vat_number != null && $user->add_tax)?$company->vat_percentage:'' }}">
                                                <input type="hidden" name="stripe_item_name" value="Tuning credit">
                                                <input type="hidden" name="stripe_item_description" value="">
                                                <input type="hidden" name="stripe_item_amount" value="">
                                                <input type="hidden" name="stripe_total_amount" value="">
                                                <input type="hidden" name="stripe_item_tax" value="">
                                                <input type="hidden" name="stripe_item_tax_percentage" value="{{ ($company->vat_number != null && $user->add_tax)?$company->vat_percentage:'' }}">
                                                <div class='form-row row'>
                                                    <div class='col-xs-12 form-group required'>
                                                        <label class='control-label'>Name on Card</label>
                                                        <input class='form-control' size='4' type='text'>
                                                    </div>
                                                </div>
                                                <div class='form-row row'>
                                                    <div class='col-xs-12 form-group card required'>
                                                        <label class='control-label'>Card Number</label>
                                                        <input autocomplete='off' class='form-control card-number' size='20' type='text'>
                                                    </div>
                                                </div>
                                                <div class='form-row row'>
                                                    <div class='col-xs-12 col-md-4 form-group cvc required'>
                                                        <label class='control-label'>CVC</label>
                                                        <input autocomplete='off' class='form-control card-cvc' placeholder='ex. 311' size='4' type='text'>
                                                    </div>
                                                    <div class='col-xs-12 col-md-4 form-group expiration required'>
                                                        <label class='control-label'>Expiration Month</label>
                                                        <input class='form-control card-expiry-month' placeholder='MM' size='2' type='text'>
                                                    </div>
                                                    <div class='col-xs-12 col-md-4 form-group expiration required'>
                                                        <label class='control-label'>Expiration Year</label>
                                                        <input class='form-control card-expiry-year' placeholder='YYYY' size='4' type='text'>
                                                    </div>
                                                </div>
                                                <div class='form-row row'>
                                                    <div class='col-md-12 error form-group hide'>
                                                        <div class='alert-danger alert'>Please correct the errors and try again.</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <button class="btn btn-primary btn-lg btn-block" type="submit">Pay Now</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($tuningEVCCreditGroup && $company->reseller_id && $user->reseller_id)
                        <div class="col-md-6 table-responsive">
                            <h2>
                                <span class="text-capitalize">EVC credits</span>
                            </h2>
                            <table class="table table-striped">
			    				<thead>
			    					<tr>
					    				<th>&nbsp;</th>
					    				<th>{{__('customer_msg.tb_header_Description')}}</th>
                                        <th>{{__('customer_msg.tb_header_From')}}</th>
                                        <th>{{__('customer_msg.tb_header_For')}}</th>
					    				<th>&nbsp;</th>
					    			</tr>
			    				</thead>
			    				<tbody>
			    					@if($groupEVCCreditTires->count() > 0)
			    						@foreach($groupEVCCreditTires as $groupCreditTire)
					    					<tr>
								    			<td>
								    				<input type="radio" name="item_credits" class='evc_items' value="{{ $groupCreditTire->amount }}" data-item-amount="{{ $groupCreditTire->pivot->for_credit }}" data-item-description="purchase {{ $groupCreditTire->amount }} evc tuning credit">
								    			</td>
								    			<td>{{ $groupCreditTire->amount }} credits</td>
								    			<td>
								    				{{ config('site.currency_sign') }}
								    				{{ number_format($groupCreditTire->pivot->from_credit, 2) }}
								    			</td>
								    			<td>
								    				{{ config('site.currency_sign') }}
								    				{{ number_format($groupCreditTire->pivot->for_credit, 2) }}
								    			</td>
								    			<td>
								    				@if($groupCreditTire->pivot->from_credit > $groupCreditTire->pivot->for_credit)
								    					Save {{ config('site.currency_sign').' '.number_format(($groupCreditTire->pivot->from_credit - $groupCreditTire->pivot->for_credit), 2) }}
								    				@endif
								    			</td>
								    		</tr>
								    		@php
								    			$isPayAble = TRUE;
								    		@endphp
							    		@endforeach
						    		@endif

						    		@if($isVatCalculation == TRUE)
							    		<tr>
							    			<td>&nbsp;</td>
							    			<td>&nbsp;</td>
							    			<td>VAT (<span class="vat_percentage">0</span>)%</td>
							    			<td>&nbsp;</td>
							    			<td>
							    				{{ config('site.currency_sign') }}
							    				<span class="vat_amount">0.00</span>
							    			</td>
							    		</tr>
						    		@endif

						    		<tr>
					    				<th>&nbsp;</th>
					    				<th>&nbsp;</th>
					    				<th>{{__('customer_msg.tb_header_OrderTotal')}}</th>
					    				<th>&nbsp;</th>
					    				<th>
					    					{{ config('site.currency_sign') }}
					    					<span class="payable-amount-evc"></span>
					    				</th>
					    			</tr>
			    				</tbody>
				    		</table>
                        </div>
                        @endif
			    	</div><!-- /.box-body -->
				    <div class="box-footer">
		                <div id="saveActions" class="form-group">
						    <div class="btn-group">
						        <button type="button" class="btn btn-danger" {{ ($isPayAble == FALSE)?'disabled=disabled':'' }} onclick="onSubmit()">
						            <span>Buy</span>
						        </button>
						    </div>
						    <a href="{{ url('customer/dashboard') }}" class="btn btn-default"><span class="fa fa-ban"></span> &nbsp;Cancel</a>
						</div>
			    	</div><!-- /.box-footer-->
		    	@else
			  		{{ __('customer.no_credit_group_of_user') }}
			  	@endif
		  	</div><!-- /.box -->
	</div>
</div>

@endsection

@push('after_scripts')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
    var payment_method = 'paypal'
    $(document).ready(function(){
        var element, itemDescription, itemAmount, totalAmount, vatPercentage;
        vatPercentage = 0.00;

        @if($isVatCalculation == TRUE)
            vatPercentage = '{{ $company->vat_percentage }}';
        @endif
        vatPercentage = parseFloat(vatPercentage);
        element = $('input[name=item_credits]:checked');
        itemDescription = element.attr('data-item-description');
        itemAmount = element.attr('data-item-amount');
        itemAmount = parseFloat(itemAmount);
        vatAmount = (itemAmount*vatPercentage/100);
        totalAmount = (itemAmount+vatAmount);
        $('input[name=item_description]').val(itemDescription);
        $('input[name=item_amount]').val(itemAmount.toFixed(2));
        $('input[name=item_tax]').val(vatAmount.toFixed(2));
        $('input[name=total_amount]').val(totalAmount.toFixed(2));
        $('input[name=stripe_item_description]').val(itemDescription);
        $('input[name=stripe_item_amount]').val(itemAmount.toFixed(2));
        $('input[name=stripe_item_tax]').val(vatAmount.toFixed(2));
        $('input[name=stripe_total_amount]').val(totalAmount.toFixed(2));
        $('input[name=stripe_item_credits]').val($('input[name=item_credits]').val());
        if ($(element).hasClass('evc_items')) {
            $('.payable-amount-evc').text(totalAmount.toFixed(2));
            $('.payable-amount').text('');
            $('input[name=credit_type]').val('evc');
            $('input[name=stripe_credit_type]').val('evc');
        } else {
            $('.payable-amount-evc').text('');
            $('.payable-amount').text(totalAmount.toFixed(2));
            $('input[name=credit_type]').val('normal');
            $('input[name=stripe_credit_type]').val('normal');
        }

        @if($isVatCalculation == TRUE)
            $('.vat_percentage').text(vatPercentage);
            $('.vat_amount').text(vatAmount.toFixed(2));
        @endif
        $('input[name=item_credits]').on('click', function(){
            element = $(this);
            if(element.prop("checked") == true){
                itemDescription = element.attr('data-item-description');
                itemAmount = element.attr('data-item-amount');
                itemAmount = parseFloat(itemAmount);
                vatAmount = (itemAmount*vatPercentage/100);
                totalAmount = (itemAmount+vatAmount);
                $('input[name=item_description]').val(itemDescription);
                $('input[name=item_amount]').val(itemAmount.toFixed(2));
                $('input[name=item_tax]').val(vatAmount.toFixed(2));
                $('input[name=total_amount]').val(totalAmount.toFixed(2));
                $('input[name=stripe_item_description]').val(itemDescription);
                $('input[name=stripe_item_amount]').val(itemAmount.toFixed(2));
                $('input[name=stripe_item_tax]').val(vatAmount.toFixed(2));
                $('input[name=stripe_total_amount]').val(totalAmount.toFixed(2));
                $('input[name=stripe_item_credits]').val(element.val());
                if ($(element).hasClass('evc_items')) {
                    $('.payable-amount-evc').text(totalAmount.toFixed(2));
                    $('.payable-amount').text('');
                    $('input[name=credit_type]').val('evc');
                    $('input[name=stripe_credit_type]').val('evc');
                } else {
                    $('.payable-amount-evc').text('');
                    $('.payable-amount').text(totalAmount.toFixed(2));
                    $('input[name=credit_type]').val('normal');
                    $('input[name=stripe_credit_type]').val('normal');
                }
                @if($isVatCalculation == TRUE)
                    $('.vat_percentage').text(vatPercentage);
                    $('.vat_amount').text(vatAmount.toFixed(2));
                @endif
            }
        });
    });
    function selectPayment(payment) {
        payment_method = payment
        if (payment === 'paypal') {
            $('#payment_img_paypal').addClass('payment-active')
            $('#payment_img_stripe').removeClass('payment-active')
            $('#stripe-details').hide();
        } else {
            $('#payment_img_paypal').removeClass('payment-active')
            $('#payment_img_stripe').addClass('payment-active')
        }
    }
    function onSubmit() {
        if (payment_method == 'paypal') {
            $('#mainForm').submit();
        } else {
            $('#stripe-details').show();
        }
    }
    $(function() {
        var $form = $(".require-validation");
        $('form.require-validation').bind('submit', function(e) {
            var $form = $(".require-validation"),
            inputSelector = ['input[type=email]', 'input[type=password]', 'input[type=text]', 'input[type=file]', 'textarea'].join(', '),
            $inputs       = $form.find('.required').find(inputSelector),
            $errorMessage = $form.find('div.error'),
            valid         = true;
            $errorMessage.addClass('hide');
            $('.has-error').removeClass('has-error');
            $inputs.each(function(i, el) {
                var $input = $(el);
                if ($input.val() === '') {
                    $input.parent().addClass('has-error');
                    $errorMessage.removeClass('hide');
                    e.preventDefault();
                }
            });
            if (!$form.data('cc-on-file')) {
                e.preventDefault();
                Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                Stripe.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val()
                }, stripeResponseHandler);
            }
        });
        function stripeResponseHandler(status, response) {
            if (response.error) {
                $('.error').removeClass('hide').find('.alert').text(response.error.message);
            } else {
                // token contains id, last4, and card type
                var token = response['id'];
                console.log(token)
                // insert the token into the form so it gets submitted to the server
                $form.find('input[type=text]').empty();
                $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                $form.get(0).submit();
            }
        }
    });
</script>
@endpush
