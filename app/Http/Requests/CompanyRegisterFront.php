<?php

namespace App\Http\Requests;

use App\Models\Company;
use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class CompanyRegisterFront extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
			'name' => 'bail|required|string|max:100',
			'main_email_address' => 'required|email|unique:companies',
			'address_line_1'=> 'bail|required|string|max:100',
			'town'=> 'bail|required|string|max:50',
			'country'=> 'bail|required|string|max:50',
			'password' => 'nullable|min:6',
            'password_confirmation' => 'nullable|required_with:password|min:6|max:20|same:password',
			'domain_prefix' => 'bail|nullable|string|max:100',
			'domain_link' => 'bail|required|unique:companies,domain_link|max:100',
        ];
    }


	 /**
     * Custom message for validation
     *
     * @return array
     */
	public function messages()
    {
        return [
            'name.required' => 'Name is required!',
            'address_line_1.required' => 'Address is required!',
            'town.required' => 'Town is required!',
			'password_confirmation.required' => 'Confirm Password field is required!',
			'domain_prefix.required' => 'Sub Domain Prefix is required!',
        ];
    }


}
