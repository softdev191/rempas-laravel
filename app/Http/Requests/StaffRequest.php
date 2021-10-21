<?php

namespace App\Http\Requests;

use App\User;
use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Auth;

class StaffRequest extends FormRequest
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
        $user = User::find($this->get('id'));
        $admin = \Auth::guard('admin')->user();
        switch ($this->method()) {
            case 'GET':{
                return [
                    'first_name'      => 'required|string|max:191',
                    'last_name'       => 'required|string|max:191',
                    'business_name'   => 'required|string|max:191',
                    'business_name'   => 'required|string|max:191',
                    'address_line_1'  => 'required|string|max:191',
                    'address_line_2'  => 'nullable|string|max:191',
                    'phone'           => 'required|max:30|regex:/^[0-9 ]+$/',
                    'county'          => 'required|string|max:191',
                    'town'            => 'required|string|max:191',
                    'post_code'       => 'nullable|string|max:191',
                    'email'           => 'required|email|unique:users,email',
                ];
            }
            case 'DELETE': {
                    return [];
                }
            case 'POST': {
                    return [
                        'first_name'      => 'required|string|max:191',
                        'last_name'       => 'required|string|max:191',
                        'business_name'   => 'required|string|max:191',
                        'business_name'   => 'required|string|max:191',
                        'address_line_1'  => 'required|string|max:191',
                        'address_line_2'  => 'nullable|string|max:191',
                        'phone'           => 'required|max:30|regex:/^[0-9 ]+$/',
                        'county'          => 'required|string|max:191',
                        'town'            => 'required|string|max:191',
                        'post_code'       => 'nullable|string|max:191',
                        'email'           => 'required|email|unique:users,email,NULL,id,company_id,'.$admin->company->id
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    return [
                        'first_name'      => 'required|string|max:191',
                        'last_name'       => 'required|string|max:191',
                        'business_name'   => 'required|string|max:191',
                        'business_name'   => 'required|string|max:191',
                        'address_line_1'  => 'required|string|max:191',
                        'address_line_2'  => 'nullable|string|max:191',
                        'phone'           => 'required|max:30|regex:/^[0-9 ]+$/',
                        'county'          => 'required|string|max:191',
                        'town'            => 'required|string|max:191',
                        'post_code'       => 'nullable|string|max:191',
                        'email'           => 'required|email|unique:users,email,'.$user->id.',id,company_id,'.$admin->company->id,
                    ];
                }
            default:break;
        }
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
