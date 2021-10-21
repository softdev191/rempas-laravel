<div class="row">
    <div class="col-md-6 col-xs-12">
        <div class="form-group {{ $errors->has('customer_note') ? ' has-error' : '' }}">
        <label>{{__('customer_msg.company_NoteCustomer')}} <small class="text-muted">({{__('customer_msg.service_Optional')}})</small></label>
        <textarea name="customer_note" placeholder="Notes to customer" class="form-control">{{ (old('customer_note')) ? old('customer_note') : $company->customer_note }}</textarea>
        @if ($errors->has('customer_note'))
            <span class="help-block">
                <strong>{{ $errors->first('customer_note') }}</strong>
            </span>
        @endif
    </div>
    </div>
</div>
