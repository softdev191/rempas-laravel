<div class="row">
    <div class="col-md-6 col-xs-12">
        <div class="form-group {{ $errors->has('mail_driver') ? ' has-error' : '' }}">
        <label>{{__('customer_msg.company_MailDriver')}}</label>
        <input type="text" name="mail_driver" readonly="readonly" value="smtp" class="form-control">
        @if ($errors->has('mail_driver'))
            <span class="help-block">
                <strong>{{ $errors->first('mail_driver') }}</strong>
            </span>
        @endif
    </div>
      <div class="form-group {{ $errors->has('mail_host') ? ' has-error' : '' }}">
        <label>{{__('customer_msg.company_MailHost')}}</label>
        <input name="mail_host" value="{{ (old('mail_host')) ? old('mail_host') : $company->mail_host }}" placeholder="Mail host" class="form-control" type="text">
        @if ($errors->has('mail_host'))
            <span class="help-block">
                <strong>{{ $errors->first('mail_host') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('mail_port') ? ' has-error' : '' }}">
        <label>{{__('customer_msg.company_MailPort')}}</label>
        <input name="mail_port" value="{{ (old('mail_port')) ? old('mail_port') : $company->mail_port }}" placeholder="Mail port" class="form-control" type="text">
        @if ($errors->has('mail_port'))
            <span class="help-block">
                <strong>{{ $errors->first('mail_port') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('mail_encryption') ? ' has-error' : '' }}">
        <label>{{__('customer_msg.company_MailEncryption')}}</label>
        <select name="mail_encryption" class="form-control">
                <option value="">
                None
            </option>
            <option value="ssl" {{
                (old('mail_encryption') == "ssl") ? "selected='selected'" : ((@$company->mail_encryption == 'ssl') ? "selected='selected'" : '') }}
                >
                SSL
            </option>
            <option value="tls"{{
                (old('mail_encryption') == "tls") ? "selected='selected'" : ((@$company->mail_encryption == 'tls') ? "selected='selected'" : '') }}
                >
                TLS
            </option>
        </select>
        @if ($errors->has('mail_encryption'))
            <span class="help-block">
                <strong>{{ $errors->first('mail_encryption') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('mail_username') ? ' has-error' : '' }}">
        <label>{{__('customer_msg.company_MailUsername')}}</label>
        <input name="mail_username" value="{{ (old('mail_username')) ? old('mail_username') : ((@$company->mail_username)?$company->mail_username:'') }}" placeholder="Mail username" class="form-control" type="text">
        @if ($errors->has('mail_username'))
            <span class="help-block">
                <strong>{{ $errors->first('mail_username') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('mail_password') ? ' has-error' : '' }}">
        <label>{{__('customer_msg.company_MailUserPassword')}}</label>
        <input type="password" name="mail_password" value="{{ (old('mail_password')) ? old('mail_password') : ((@$company->mail_password)?$company->mail_password:'') }}" placeholder="Mail password" class="form-control" type="text">
        @if ($errors->has('mail_password'))
            <span class="help-block">
                <strong>{{ $errors->first('mail_password') }}</strong>
            </span>
        @endif
    </div>
    </div>
</div>
