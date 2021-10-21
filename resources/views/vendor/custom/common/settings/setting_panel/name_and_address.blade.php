<div class="row">
    <div class="col-md-6 col-xs-12">
        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }} ">
        <label>{{__('customer_msg.tb_header_Name')}}</label>
        <input name="name" value="{{ (old('name')) ? old('name') : $company->name }}" placeholder="Name" class="form-control" type="text">
        @if ($errors->has('name'))
            <span class="help-block">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('address_line_1') ? ' has-error' : '' }}">
        <label>{{__('customer_msg.contactInfo_AddressLine1')}}</label>
        <input name="address_line_1" value="{{ (old('address_line_1')) ? old('address_line_1') : $company->address_line_1 }}" placeholder="Address line 1" class="form-control" type="text">
        @if ($errors->has('address_line_1'))
            <span class="help-block">
                <strong>{{ $errors->first('address_line_1') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('address_line_2') ? ' has-error' : '' }}">
        <label>{{__('customer_msg.contactInfo_AddressLine2')}}<small class="text-muted">({{__('customer_msg.service_Optional')}})</small></label>
        <input name="address_line_2" value="{{ (old('address_line_2')) ? old('address_line_2') : $company->address_line_2 }}" placeholder="Address line 2" class="form-control" type="text">
        @if ($errors->has('address_line_2'))
            <span class="help-block">
                <strong>{{ $errors->first('address_line_2') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group {{ $errors->has('town') ? ' has-error' : '' }}">
        <label>{{__('customer_msg.contactInfo_Town')}}</label>
        <input name="town" value="{{ (old('town')) ? old('town') : $company->town }}" placeholder="Town" class="form-control" type="text">
        @if ($errors->has('town'))
            <span class="help-block">
                <strong>{{ $errors->first('town') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group  {{ $errors->has('post_code') ? ' has-error' : '' }}">
        <label>{{__('customer_msg.contactInfo_PostCode')}} <small class="text-muted">({{__('customer_msg.service_Optional')}})</small></label>
        <input name="post_code" value="{{ (old('post_code')) ? old('post_code') : $company->post_code }}" placeholder="Post Code" class="form-control" type="text">
        @if ($errors->has('post_code'))
            <span class="help-block">
                <strong>{{ $errors->first('post_code') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group {{ $errors->has('country') ? ' has-error' : '' }}">
        <label>{{__('customer_msg.contactInfo_County')}}</label>
        <input name="country" value="{{ (old('country')) ? old('country') : $company->country }}" placeholder="Country" class="form-control" type="text">
        @if ($errors->has('country'))
            <span class="help-block">
                <strong>{{ $errors->first('country') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('state') ? ' has-error' : '' }}">
        <label>{{__('customer_msg.company_StateProvince')}} <small class="text-muted">({{__('customer_msg.service_Optional')}})</small></label>
        <input name="state" value="{{ (old('state')) ? old('state') : $company->state }}" placeholder="State/Province" class="form-control" type="text">
        @if ($errors->has('state'))
            <span class="help-block">
                <strong>{{ $errors->first('state') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('file') ? ' has-error' : '' }}">
        <label>{{__('customer_msg.company_Logo')}}</label>
        <input name="file" type="file">
        @if ($errors->has('file'))
            <span class="help-block">
                <strong>{{ $errors->first('file') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('theme_color') ? ' has-error' : '' }}">
        <label>{{__('customer_msg.company_ThemeColor')}}</label>
        <input name="theme_color" type="color" value="{{ (old('theme_color')) ? old('theme_color') : ($company->theme_color ? $company->theme_color : '#fff') }}" class="form-control">
        @if ($errors->has('theme_color'))
            <span class="help-block">
                <strong>{{ $errors->first('theme_color') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('copy_right_text') ? ' has-error' : '' }}">
        <label>{{__('customer_msg.company_Copyright')}}</small></label>
        <input name="copy_right_text" value="{{ (old('copy_right_text')) ? old('copy_right_text') : $company->copy_right_text }}" placeholder="Copy right text" class="form-control" type="text">
        @if ($errors->has('copy_right_text'))
            <span class="help-block">
                <strong>{{ $errors->first('copy_right_text') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('link_name') ? ' has-error' : '' }}">
        <label>{{__('customer_msg.company_CustomLinkName')}}</small></label>
        <input name="link_name" value="{{ (old('link_name')) ? old('link_name') : $company->link_name }}" placeholder="Custom Link Name" class="form-control" type="text">
        @if ($errors->has('link_name'))
            <span class="help-block">
                <strong>{{ $errors->first('link_name') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('link_value') ? ' has-error' : '' }}">
        <label>{{__('customer_msg.company_CustomLink')}}</small></label>
        <input name="link_value" value="{{ (old('link_value')) ? old('link_value') : $company->link_value }}" placeholder="Custom Link URL" class="form-control" type="text">
        @if ($errors->has('link_value'))
            <span class="help-block">
                <strong>{{ $errors->first('link_value') }}</strong>
            </span>
        @endif
    </div>
    </div>
</div>
