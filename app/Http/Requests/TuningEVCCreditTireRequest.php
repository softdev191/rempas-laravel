<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TuningEVCCreditTireRequest extends FormRequest
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
        $user = \Auth::guard('admin')->user();

        switch ($this->method()) {
            case 'GET':{
                return [

                ];
            }
            case 'DELETE': {
                    return [];
                }
            case 'POST': {
                    return [
                        'amount' =>'required|'.Rule::unique('tuning_credit_tires')->where('company_id', $user->company_id)->where('group_type', 'evc').'|regex:/^\d*(\.\d{1,2})?$/|max:8',
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    return [
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
