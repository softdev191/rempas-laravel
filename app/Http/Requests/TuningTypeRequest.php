<?php

namespace App\Http\Requests;

use App\Models\TuningType;
use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class TuningTypeRequest extends FormRequest
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
        $tuningType = TuningType::find($this->get('id'));
        $this->user = \Auth::guard('admin')->user();

        switch ($this->method()) {
            case 'GET':{
                return [
                    'label' => 'required|string|min:3|max:191',
                    'credits' => 'required|regex:/^\d*(\.\d{1,2})?$/|max:8'
                ];
            }
            case 'DELETE': {
                    return [];
                }
            case 'POST': {
                    return [
                        'label' => 'required|string|min:3|max:191|unique:tuning_types,label,NULL,id,company_id,'.$this->user->company_id,
                        'credits' => 'required|regex:/^\d*(\.\d{1,2})?$/|max:8'
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    return [
                        'label' => 'required|string|min:3|max:191|unique:tuning_types,label,'.$tuningType->id.',id,company_id,'.$this->user->company_id,
                        'credits' => 'required|regex:/^\d*(\.\d{1,2})?$/|max:8'
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
