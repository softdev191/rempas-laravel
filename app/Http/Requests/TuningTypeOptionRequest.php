<?php

namespace App\Http\Requests;

use App\Models\TuningType;
use App\Http\Requests\Request;
use Illuminate\Validation\Rule;
use App\Models\TuningTypeOption;
use Illuminate\Foundation\Http\FormRequest;

class TuningTypeOptionRequest extends FormRequest
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
        
        $tuningTypeOption = TuningTypeOption::find($this->get('id'));
    
        switch ($this->method()) {
            case 'GET':{
                return [
                    'tuning_type_id' => 'required|integer',
                    'label' => 'required|string|min:3|max:191',
                    'tooltip' => 'nullable|string|min:3|max:191',
                    'credits' => 'required|regex:/^\d*(\.\d{1,2})?$/|max:8'
                ];
            }
            case 'DELETE': {
                    return [];
                }
            case 'POST': {
                    return [
                        'tuning_type_id' => 'required|integer',
                        'label' => 'required|string|min:3|max:191|'.Rule::unique('tuning_type_options')->where('tuning_type_id', $this->tuning_type_id),
                        'tooltip' => 'nullable|string|min:3|max:191',
                        'credits' => 'required|regex:/^\d*(\.\d{1,2})?$/|max:8'
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    return [
                        'tuning_type_id' => 'required|integer',
                        'label' => 'required|string|min:3|max:191|unique:tuning_type_options,label,'.$tuningTypeOption->id.',id,tuning_type_id,'.$this->tuning_type_id,
                        'tooltip' => 'nullable|string|min:3|max:191',
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
            'tuning_type_id.required'=>'The tuning type is required. please navigate tuning options in right manner.'
        ];
    }
}
