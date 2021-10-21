<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class TicketsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (\Auth::guard('admin')->check() || \Auth::guard('customer')->check());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
       switch ($this->method()) {
            case 'GET':{
                return [
                    'subject' => 'bail|required_if:file_servcie_id,0',
                    'message' => 'bail|required|string'
                ];
            }
            case 'DELETE': {
                    return [];
                }
            case 'POST': {
                    return [
                        'subject' => 'bail|required_if:file_servcie_id,0',
                        'message' => 'bail|required|string'
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    return [
                        'message' => 'bail|required|string'
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
            'subject.required_if'=> 'The subject field is required.'
        ];
    }
}
