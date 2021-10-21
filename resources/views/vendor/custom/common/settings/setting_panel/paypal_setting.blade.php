<p>
    <strong>Note: </strong>{{__('customer_msg.company_NoteDesc')}}: <a href="https://developer.paypal.com/developer/applications">https://developer.paypal.com/developer/applications</a>. Click on : MY APPS AND CREDENTIALS, Scroll down to REST API apps and click CREATE APP.
        </p>
  <div class="row">
    <div class="col-md-6 col-xs-12">
          <div class="form-group {{ $errors->has('paypal_client_id') ? ' has-error' : '' }}">
            <label>{{__('customer_msg.company_PaypalId')}}</label>
            <input name="paypal_client_id" value="{{ (old('paypal_client_id'))?old('paypal_client_id'):((@$company->paypal_client_id)?$company->paypal_client_id:'') }}" placeholder="Paypal client id" class="form-control" type="text">
            @if ($errors->has('paypal_client_id'))
                <span class="help-block">
                    <strong>{{ $errors->first('paypal_client_id') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group {{ $errors->has('paypal_secret') ? ' has-error' : '' }}">
            <label>{{__('customer_msg.company_PaypalSecret')}}</label>
            <input name="paypal_secret" value="{{ (old('paypal_secret'))?old('paypal_secret'):((@$company->paypal_secret)?$company->paypal_secret:'') }}" placeholder="Paypal secret" class="form-control" type="text">
            @if ($errors->has('paypal_secret'))
                <span class="help-block">
                    <strong>{{ $errors->first('paypal_secret') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group {{ $errors->has('paypal_currency_code') ? ' has-error' : '' }}">
            <label>{{__('customer_msg.company_PaypalCurrencyCode')}}</label>
            @if (Auth::id() == 11)
            <input name="paypal_currency_code" value="{{ (old('paypal_currency_code'))?old('paypal_currency_code'):((@$company->paypal_currency_code)?$company->paypal_currency_code:'') }}" placeholder="Paypal currency code" class="form-control" type="text">
            @else
            <select name="paypal_currency_code" class="form-control">

                <option value="null" {{
                    (old('paypal_currency_code') == "null") ? "selected='selected'" : ((@$company->paypal_currency_code == 'null') ? "selected='selected'" : '') }}
                    >
                    Select Currency
                </option>
                <option value="AUD" {{
                    (old('paypal_currency_code') == "AUD") ? "selected='selected'" : ((@$company->paypal_currency_code == 'AUD') ? "selected='selected'" : '') }}
                    >
                    AUD
                </option>
                <option value="BRL" {{
                    (old('paypal_currency_code') == "BRL") ? "selected='selected'" : ((@$company->paypal_currency_code == 'BRL') ? "selected='selected'" : '') }}
                    >
                    BRL
                </option>
                <option value="CAD" {{
                    (old('paypal_currency_code') == "CAD") ? "selected='selected'" : ((@$company->paypal_currency_code == 'CAD') ? "selected='selected'" : '') }}
                    >
                    CAD
                </option>
                <option value="CZK" {{
                    (old('paypal_currency_code') == "CZK") ? "selected='selected'" : ((@$company->paypal_currency_code == 'CZK') ? "selected='selected'" : '') }}
                    >
                    CZK
                </option>
                <option value="DKK" {{
                    (old('paypal_currency_code') == "DKK") ? "selected='selected'" : ((@$company->paypal_currency_code == 'DKK') ? "selected='selected'" : '') }}
                    >
                    DKK
                </option>
                <option value="HKD" {{
                    (old('paypal_currency_code') == "HKD") ? "selected='selected'" : ((@$company->paypal_currency_code == 'HKD') ? "selected='selected'" : '') }}
                    >
                    HKD
                </option>
                <option value="ILS" {{
                    (old('paypal_currency_code') == "ILS") ? "selected='selected'" : ((@$company->paypal_currency_code == 'ILS') ? "selected='selected'" : '') }}
                    >
                    ILS
                </option>
                <option value="MXN" {{
                    (old('paypal_currency_code') == "MXN") ? "selected='selected'" : ((@$company->paypal_currency_code == 'MXN') ? "selected='selected'" : '') }}
                    >
                    MXN
                </option>
                <option value="NOK" {{
                    (old('paypal_currency_code') == "NOK") ? "selected='selected'" : ((@$company->paypal_currency_code == 'NOK') ? "selected='selected'" : '') }}
                    >
                    NOK
                </option>
                <option value="EUR" {{
                    (old('paypal_currency_code') == "EUR") ? "selected='selected'" : ((@$company->paypal_currency_code == 'EUR') ? "selected='selected'" : '') }}
                    >
                    EUR
                </option>
                <option value="NZD" {{
                    (old('paypal_currency_code') == "NZD") ? "selected='selected'" : ((@$company->paypal_currency_code == 'NZD') ? "selected='selected'" : '') }}
                    >
                    NZD
                </option>
                <option value="INR" {{
                    (old('paypal_currency_code') == "INR") ? "selected='selected'" : ((@$company->paypal_currency_code == 'INR') ? "selected='selected'" : '') }}
                    >
                    INR
                </option>
                <option value="PHP" {{
                    (old('paypal_currency_code') == "PHP") ? "selected='selected'" : ((@$company->paypal_currency_code == 'PHP') ? "selected='selected'" : '') }}
                    >
                    PHP
                </option>
                <option value="PLN" {{
                    (old('paypal_currency_code') == "PLN") ? "selected='selected'" : ((@$company->paypal_currency_code == 'PLN') ? "selected='selected'" : '') }}
                    >
                    PLN
                </option>
                <option value="GBP" {{
                    (old('paypal_currency_code') == "GBP") ? "selected='selected'" : ((@$company->paypal_currency_code == 'GBP') ? "selected='selected'" : '') }}
                    >
                    GBP
                </option>
                <option value="SGD" {{
                    (old('paypal_currency_code') == "SGD") ? "selected='selected'" : ((@$company->paypal_currency_code == 'SGD') ? "selected='selected'" : '') }}
                    >
                    SGD
                </option>
                <option value="SEK" {{
                    (old('paypal_currency_code') == "SEK") ? "selected='selected'" : ((@$company->paypal_currency_code == 'SEK') ? "selected='selected'" : '') }}
                    >
                    SEK
                </option>
                <option value="CHF" {{
                    (old('paypal_currency_code') == "CHF") ? "selected='selected'" : ((@$company->paypal_currency_code == 'CHF') ? "selected='selected'" : '') }}
                    >
                    CHF
                </option>
                <option value="THB" {{
                    (old('paypal_currency_code') == "THB") ? "selected='selected'" : ((@$company->paypal_currency_code == 'THB') ? "selected='selected'" : '') }}
                    >
                    THB
                </option>
                <option value="USD" {{
                    (old('paypal_currency_code') == "USD") ? "selected='selected'" : ((@$company->paypal_currency_code == 'USD') ? "selected='selected'" : '') }}
                    >
                    USD
                </option>
            </select>
            @endif
            @if ($errors->has('paypal_currency_code'))
                <span class="help-block">
                    <strong>{{ $errors->first('paypal_currency_code') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>
