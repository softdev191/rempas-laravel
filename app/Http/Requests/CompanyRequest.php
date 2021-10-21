<?php

namespace App\Http\Requests;

use App\Models\Company;
use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::guard('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $company = Company::find($this->get('id'));
        
        switch ($this->method()) {
            case 'GET':{
                return [
                    'name' => 'bail|required|string|max:100', 
                    'address_line_1'=> 'bail|required|string|max:100', 
                    'address_line_2'=> 'bail|nullable|string|max:100', 
                    'town'=> 'bail|required|string|max:50', 
                    'post_code'=> 'bail|nullable|string|max:30', 
                    'country'=> 'bail|required|string|max:50', 
                    'state'=> 'bail|nullable|string|max:50',
                    'file'=> 'bail|nullable|image|mimes:jpg,png,jpeg', 
                    'copy_right_text'=> 'bail|nullable|string|max:100',
                    'domain_link'=> 'bail|required|url|unique:companies,domain_link|max:100',
                    'bank_account'=> 'bail|nullable|string|max:100', 
                    'bank_identification_code'=> 'bail|nullable|string|max:100',  
                    'vat_number'=> 'bail|nullable|string|max:100',
                    'vat_percentage'=> 'bail|nullable|required_with:vat_number|regex:/^\d*(\.\d{1,2})?$/|max:8',
                    'main_email_address'=> 'bail|required|email|unique:companies,main_email_address|unique:users,email|max:100', 
                    'support_email_address'=> 'bail|nullable|email|max:100', 
                    'billing_email_address'=> 'bail|nullable|email|max:100',
                    'customer_note'=> 'bail|nullable|string',
                    'mail_driver'=> 'bail|nullable|string|max:20', 
                    'mail_host'=> 'bail|nullable|string|max:100', 
                    'mail_port'=> 'bail|nullable|integer', 
                    'mail_username'=> 'bail|nullable|email|max:100', 
                    'mail_password'=> 'bail|nullable|string|max:100', 
                    //'paypal_mode'=> 'bail|nullable|string|max:100', 
                    'paypal_client_id'=> 'bail|nullable|string|max:200',
                    'paypal_secret'=> 'bail|nullable|string|max:200',
                    'paypal_currency_code'=> 'bail|nullable|string|max:10',
                ];
            }
            case 'DELETE': {
                    return [];
                }
            case 'POST': {
                    return [
                        'name' => 'bail|required|string|max:100', 
                        'address_line_1'=> 'bail|required|string|max:100', 
                        'address_line_2'=> 'bail|nullable|string|max:100', 
                        'town'=> 'bail|required|string|max:50', 
                        'post_code'=> 'bail|nullable|string|max:30', 
                        'country'=> 'bail|required|string|max:50', 
                        'state'=> 'bail|nullable|string|max:50',
                        'file'=> 'bail|nullable|image|mimes:jpg,png,jpeg', 
                        'copy_right_text'=> 'bail|nullable|string|max:100',
                        'domain_link'=> 'bail|required|url|unique:companies,domain_link|max:100',
                        'bank_account'=> 'bail|nullable|string|max:100', 
                        'bank_identification_code'=> 'bail|nullable|string|max:100',  
                        'vat_number'=> 'bail|nullable|string|max:100',
                        'vat_percentage'=> 'bail|nullable|required_with:vat_number|regex:/^\d*(\.\d{1,2})?$/|max:8',
                        'main_email_address'=> 'bail|required|email|unique:companies,main_email_address|unique:users,email|max:100', 
                        'support_email_address'=> 'bail|nullable|email|max:100', 
                        'billing_email_address'=> 'bail|nullable|email|max:100',
                        'customer_note'=> 'bail|nullable|string', 
                        'mail_driver'=> 'bail|nullable|string|max:20', 
                        'mail_host'=> 'bail|nullable|string|max:100', 
                        'mail_port'=> 'bail|nullable|integer', 
                        'mail_username'=> 'bail|nullable|email|max:100', 
                        'mail_password'=> 'bail|nullable|string|max:100',
                        //'paypal_mode'=> 'bail|nullable|string|max:100', 
                        'paypal_client_id'=> 'bail|nullable|string|max:200',
                        'paypal_secret'=> 'bail|nullable|string|max:200',
                        'paypal_currency_code'=> 'bail|nullable|string|max:10',
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    
                    return [
                        'name' => 'bail|required|string|max:100', 
                        'address_line_1'=> 'bail|required|string|max:100', 
                        'address_line_2'=> 'bail|nullable|string|max:100', 
                        'town'=> 'bail|required|string|max:50', 
                        'post_code'=> 'bail|nullable|string|max:30', 
                        'country'=> 'bail|required|string|max:50', 
                        'state'=> 'bail|nullable|string|max:50',
                        'file'=> 'bail|nullable|image|mimes:jpg,png,jpeg', 
                        'copy_right_text'=> 'bail|nullable|string|max:100',
                        'domain_link'=> 'bail|required|url|unique:companies,domain_link,'.$company->id.',id|max:100',
                        'bank_account'=> 'bail|nullable|string|max:100', 
                        'bank_identification_code'=> 'bail|nullable|string|max:100',  
                        'vat_number'=> 'bail|nullable|string|max:100',
                        'vat_percentage'=> 'bail|nullable|required_with:vat_number|regex:/^\d*(\.\d{1,2})?$/|max:8',
                        'main_email_address'=> 'bail|required|email|unique:companies,main_email_address,'.$company->id.'|unique:users,email,'.$company->owner->id.'|max:100', 
                        'support_email_address'=> 'bail|nullable|email|max:100', 
                        'billing_email_address'=> 'bail|nullable|email|max:100',
                        'customer_note'=> 'bail|nullable|string', 
                        'mail_driver'=> 'bail|nullable|string|max:20', 
                        'mail_host'=> 'bail|nullable|string|max:100', 
                        'mail_port'=> 'bail|nullable|integer', 
                        'mail_username'=> 'bail|nullable|email|max:100', 
                        'mail_password'=> 'bail|nullable|string|max:100',
                        //'paypal_mode'=> 'bail|nullable|string|max:100', 
                        'paypal_client_id'=> 'bail|nullable|string|max:200',
                        'paypal_secret'=> 'bail|nullable|string|max:200',
                        'paypal_currency_code'=> 'bail|nullable|string|max:10',
                    ];
                }
            default:break;
        }
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     *
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if($this->has('file')){
                if ($this->file('file')->getClientSize() > '5242880') {
                    $validator->errors()->add('file', 'File shouldn\'t be greater than 5 MB. Please select another file.');
                }
            }
        });
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }
}
