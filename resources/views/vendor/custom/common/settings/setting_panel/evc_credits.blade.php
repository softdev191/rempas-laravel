<p>To Activate the reseller function enter reseller credentials.</p>
<div class="row">
    <div class="col-md-6 col-xs-12">
        <div class="form-group {{ $errors->has('reseller_id') ? ' has-error' : '' }}">
            <label>{{__('customer_msg.company_ResellerId')}}</label>
            <input name="reseller_id" value="{{ (old('reseller_id'))?old('reseller_id'):((@$company->reseller_id)?$company->reseller_id:'') }}" placeholder="Reseller Id" class="form-control" type="text">
            @if ($errors->has('reseller_id'))
                <span class="help-block">
                    <strong>{{ $errors->first('reseller_id') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group {{ $errors->has('reseller_password') ? ' has-error' : '' }}">
            <label>{{__('customer_msg.company_ResellerPassword')}}</label>
            <input name="reseller_password" value="{{ (old('reseller_password'))?old('reseller_password'):((@$company->reseller_password)?$company->reseller_password:'') }}" placeholder="Reseller Password" class="form-control" type="text">
            @if ($errors->has('reseller_password'))
                <span class="help-block">
                    <strong>{{ $errors->first('reseller_password') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>
