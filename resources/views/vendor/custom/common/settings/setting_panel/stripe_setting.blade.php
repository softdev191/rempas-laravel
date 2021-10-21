<div class="row">
    <div class="col-md-6 col-xs-12">
        <div class="form-group {{ $errors->has('stripe_key') ? ' has-error' : '' }}">
            <label>{{__('customer_msg.company_StripeKey')}} <small class="text-muted">(optional)</small></label>
            <input name="stripe_key" value="{{ (old('stripe_key')) ? old('stripe_key') : $company->stripe_key }}" placeholder="Stripe Key" class="form-control" type="text">
            @if ($errors->has('stripe_key'))
                <span class="help-block">
                    <strong>{{ $errors->first('stripe_key') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group {{ $errors->has('stripe_secret') ? ' has-error' : '' }}">
            <label>{{__('customer_msg.company_StripeSecret')}} <small class="text-muted">(optional)</small></label>
            <input name="stripe_secret" value="{{ (old('stripe_secret')) ? old('stripe_secret') : $company->stripe_secret }}" placeholder="Stripe Secret" class="form-control" type="text">
            @if ($errors->has('stripe_secret'))
                <span class="help-block">
                    <strong>{{ $errors->first('stripe_secret') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>
