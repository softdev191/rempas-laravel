<div class="row">
    <div class="col-md-6 col-xs-12">
        <div class="form-group {{ $errors->has('bank_account') ? ' has-error' : '' }}">
        <label>{{__('customer_msg.company_BankAccount')}} <small class="text-muted">(optional)</small></label>
        <input name="bank_account" value="{{ (old('bank_account')) ? old('bank_account') : $company->bank_account }}" placeholder="Bank account " class="form-control" type="text">
        @if ($errors->has('bank_account'))
            <span class="help-block">
                <strong>{{ $errors->first('bank_account') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group {{ $errors->has('bank_identification_code') ? ' has-error' : '' }}">
        <label>{{__('customer_msg.company_BankCode')}} <small class="text-muted">({{__('customer_msg.service_Optional')}})</small></label>
        <input name="bank_identification_code" value="{{ (old('bank_identification_code')) ? old('bank_identification_code') : $company->bank_identification_code }}" placeholder="Bank identification code (BIC)" class="form-control" type="text">
        @if ($errors->has('bank_identification_code'))
            <span class="help-block">
                <strong>{{ $errors->first('bank_identification_code') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group {{ $errors->has('vat_number') ? ' has-error' : '' }}">
        <label>{{__('customer_msg.company_VatNum')}}</label>
        <input name="vat_number" value="{{ (old('vat_number')) ? old('vat_number') : $company->vat_number }}" placeholder="VAT" class="form-control" type="text">
        @if ($errors->has('vat_number'))
            <span class="help-block">
                <strong>{{ $errors->first('vat_number') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group {{ $errors->has('vat_percentage') ? ' has-error' : '' }}">
        <label>VAT%</label>
        <input name="vat_percentage" value="{{ (old('vat_percentage')) ? old('vat_percentage') : $company->vat_percentage }}" placeholder="VAT%" class="form-control" type="text" {{ (old('vat_number') != null)?'':(@$company->vat_number != null)?'':'disabled:disabled' }}>
        @if ($errors->has('vat_percentage'))
            <span class="help-block">
                <strong>{{ $errors->first('vat_percentage') }}</strong>
            </span>
        @endif
    </div>
    </div>
</div>
