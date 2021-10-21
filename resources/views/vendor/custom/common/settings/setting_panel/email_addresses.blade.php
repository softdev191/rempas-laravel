<div class="row">
    <div class="col-md-6 col-xs-12">
        <div class="form-group {{ $errors->has('main_email_address') ? ' has-error' : '' }}">
        <label>{{__('customer_msg.company_MainEmail')}}</label>
        <input name="main_email_address" value="{{ (old('main_email_address')) ? old('main_email_address') : $company->main_email_address }}" placeholder="Main email address" class="form-control" type="text">
        @if ($errors->has('main_email_address'))
            <span class="help-block">
                <strong>{{ $errors->first('main_email_address') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('support_email_address') ? ' has-error' : '' }}">
        <label>{{__('customer_msg.company_SupportEmail')}}</label>
        <input name="support_email_address" value="{{ (old('support_email_address')) ? old('support_email_address') : $company->support_email_address }}" placeholder="Support email address" class="form-control" type="text">
        @if ($errors->has('support_email_address'))
            <span class="help-block">
                <strong>{{ $errors->first('support_email_address') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('billing_email_address') ? ' has-error' : '' }}">
        <label>{{__('customer_msg.company_BillingEmail')}}</label>
        <input name="billing_email_address" value="{{ (old('billing_email_address')) ? old('billing_email_address') : $company->billing_email_address }}" placeholder="Billing email address" class="form-control" type="text">
        @if ($errors->has('billing_email_address'))
            <span class="help-block">
                <strong>{{ $errors->first('billing_email_address') }}</strong>
            </span>
        @endif
    </div>
    </div>
</div>
