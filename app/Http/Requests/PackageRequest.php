<?php

namespace App\Http\Requests;

use App\Models\Package;
use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class PackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(\Auth::guard('admin')->check()){
            if(\Auth::guard('admin')->user()->is_master == 1){
                return TRUE;
            }else{
                return TRUE;
            }
        }else{
            return FALSE;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $package = Package::find($this->get('id'));
        switch ($this->method()) {
            case 'GET':{
                return [
                    'name' => 'required|string|unique:packages,name|min:3|max:100',
                    'billing_interval' => 'required|string|max:50',
                    'amount' => 'required|regex:/^\d*(\.\d{1,2})?$/|max:8',
                ];
            }
            case 'DELETE': {
                    return [];
                }
            case 'POST': {
                    return [
                        'name' => 'required|string|min:3|max:100',
                        'billing_interval' => 'required|string|unique:packages,name|max:50',
                        'amount' => 'required|regex:/^\d*(\.\d{1,2})?$/|max:8',
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    return [
                        'name' => 'required|string|min:3|max:100',
                        'billing_interval' => 'required|string|unique:packages,name,'.$package->id.',id|max:50',
                        'amount' => 'required|regex:/^\d*(\.\d{1,2})?$/|max:8',
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
        ];
    }
}
