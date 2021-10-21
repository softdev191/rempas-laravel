<?php

namespace App\Http\Requests;

use App\Models\TuningCreditGroup;
use Illuminate\Validation\Rule;
use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class TuningCreditGroupRequest extends FormRequest
{
    private $user, $tuningCreditTires;
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
        $this->user = \Auth::guard('admin')->user();

        $tuningCreditGroup = TuningCreditGroup::find($this->get('id'));
        $this->tuningCreditTires = \App\Models\TuningCreditTire::where('company_id', $this->user->company_id)->where('group_type', 'normal')->orderBy('amount', 'ASC')->get();

        switch ($this->method()) {
            case 'GET':{
            }
            case 'DELETE': {
            }
            case 'POST': {
                    $rules = [];

                    $rules['name'] = 'required|string|min:3|max:191|unique:tuning_credit_groups,name,NULL,id,company_id,'.$this->user->company_id;
                    if($this->tuningCreditTires->count() > 0){
                        foreach($this->tuningCreditTires as $tuningCreditTire){

                            $rules['credit_tires.'.$tuningCreditTire->id.'.from_credit'] = 'bail|required|greater_than_or_equal:credit_tires.'.$tuningCreditTire->id.'.for_credit|regex:/^\d*(\.\d{1,2})?$/';
                            $rules['credit_tires.'.$tuningCreditTire->id.'.for_credit'] = 'bail|required|regex:/^\d*(\.\d{1,2})?$/';
                        }
                    }
                    return $rules;
                }
            case 'PUT':
            case 'PATCH': {
                    $rules = [];
                    $rules['name'] = 'required|string|min:3|max:191|unique:tuning_credit_groups,name,'.$tuningCreditGroup->id.',id,company_id,'.$this->user->company_id;
                    if($this->tuningCreditTires->count() > 0){
                        foreach($this->tuningCreditTires as $tuningCreditTire){
                            $rules['credit_tires.'.$tuningCreditTire->id.'.from_credit'] = 'bail|required|greater_than_or_equal:credit_tires.'.$tuningCreditTire->id.'.for_credit|regex:/^\d*(\.\d{1,2})?$/';
                            $rules['credit_tires.'.$tuningCreditTire->id.'.for_credit'] = 'bail|required|regex:/^\d*(\.\d{1,2})?$/';
                        }
                    }
                    return $rules;
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
        $messages = [];
        if($this->tuningCreditTires->count() > 0){
                foreach($this->tuningCreditTires as $tuningCreditTire){
                    $messages['credit_tires.'.$tuningCreditTire->id.'.from_credit.required'] = 'The '.$tuningCreditTire->amount.' credit from field is required.';
                    $messages['credit_tires.'.$tuningCreditTire->id.'.from_credit.greater_than_or_equal'] = 'The '.$tuningCreditTire->amount.' credit from must be greater than or equal '.$tuningCreditTire->amount.' credit for.';
                    $messages['credit_tires.'.$tuningCreditTire->id.'.from_credit.regex'] = 'The '.$tuningCreditTire->amount.' credit from format is invalid.';
                    //$messages['credit_tires.'.$tuningCreditTire->id.'.from.max'] = '';

                    $messages['credit_tires.'.$tuningCreditTire->id.'.for_credit.required'] = 'The '.$tuningCreditTire->amount.' credit for field is required.';
                    $messages['credit_tires.'.$tuningCreditTire->id.'.for_credit.regex'] = 'The '.$tuningCreditTire->amount.' credit for format is invalid.';
                    //$messages['credit_tires.'.$tuningCreditTire->id.'.for.max'] = '';
                }
        }
        return $messages;
        return [];
    }
}
