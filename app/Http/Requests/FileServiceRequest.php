<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class FileServiceRequest extends FormRequest
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
                $rules = [
                    'user_id'=>'bail|required|integer',
                    'make' => 'bail|required|string|max:191',
                    'model' => 'bail|required|string|max:191',
                    'generation' => 'bail|required|string|max:191',
                    'engine' => 'bail|required|string|max:191',
                    'ecu' => 'bail|required|string|max:191',
					'year' => 'bail|required|string|max:191',
                    'engine_hp' => 'bail|nullable|integer',
                    'license_plate' => 'bail|required|string|max:191',
                    'vin' => 'bail|nullable|string|max:191',
                    'note_to_engineer' => 'bail|nullable|string|max:191',
                    'notes_by_engineer' => 'bail|nullable|string|max:191',
                    'tuning_type_id' => 'bail|required|integer'
                ];

                if($this->uploaded_file == null){
                    $rules['file'] = 'bail|required';
                }
                
                return $rules;
            }
            case 'DELETE': {
                    return [];
                }
            case 'POST': {
                    $rules =  [
                        'user_id'=>'bail|required|integer',
                        'make' => 'bail|required|string|max:191',
                        'model' => 'bail|required|string|max:191',
                        'generation' => 'bail|required|string|max:191',
                        'engine' => 'bail|required|string|max:191',
                        'ecu' => 'bail|required|string|max:191',
						'year' => 'bail|required|string|max:191',
                        'engine_hp' => 'bail|nullable|integer',
                        'license_plate' => 'bail|required|string|max:191',
                        'vin' => 'bail|nullable|string|max:191',
                        'note_to_engineer' => 'bail|nullable|string|max:191',
                        'notes_by_engineer' => 'bail|nullable|string|max:191',
                        'tuning_type_id' => 'bail|required|integer'
                    ];

                    if($this->uploaded_file == null){
                        $rules['file'] = 'bail|required';
                    }
                    
                    return $rules;
                }
            case 'PUT':
            case 'PATCH': {
                    $rules =  [
                        'user_id'=>'bail|required|integer',
                        'make' => 'bail|required|string|max:191',
                        'model' => 'bail|required|string|max:191',
                        'generation' => 'bail|required|string|max:191',
                        'engine' => 'bail|required|string|max:191',
                        'ecu' => 'bail|required|string|max:191',
						'year' => 'bail|required|string|max:191',
                        'engine_hp' => 'bail|nullable|integer',
                        'license_plate' => 'bail|required|string|max:191',
                        'vin' => 'bail|nullable|string|max:191',
                        'note_to_engineer' => 'bail|nullable|string|max:191',
                        'notes_by_engineer' => 'bail|nullable|string|max:191'
                    ];
                    
                    return $rules;
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
                if ($this->file('file')->getClientSize() > '10485760') {
                    $validator->errors()->add('file', 'File shouldn\'t be greater than 10 MB. Please select another file.');
                }
            }
            $user = \Auth::guard('customer')->user();

            if(!$user->company->owner->is_master){
                if(!$user->company->owner->hasActiveSubscription()){
                    $validator->errors()->add('user', 'The company havn\'t subscribed any plan or plan hasn\'t active. Please contact to your company.');
                }
            }

            if($user->tuning_credits <= 0){
                $validator->errors()->add('user', __('customer.not_enough_credits'));
            }else{
                $requestTuningType = \App\Models\TuningType::find($this->get('tuning_type_id'));
                if($requestTuningType){
                    $tuningTypeCredits = $requestTuningType->credits;
					/*if(($tuningTypeCredits == 0) && empty($this->get('tuning_type_options'))){
                        $validator->errors()->add('user', 'Please select atleast one tuning type option.');
                    }*/
                    if($user->tuning_credits < $tuningTypeCredits){
                        $validator->errors()->add('user', __('customer.not_enough_credits'));
                    }else{
                        $tuningTypeOptions = \App\Models\TuningTypeOption::find($this->get('tuning_type_options'));
                        if($tuningTypeOptions){
                            $tuningTypeOptionsCredits = $tuningTypeOptions->sum('credits');
                            $totalFilecredits = ($tuningTypeCredits+$tuningTypeOptionsCredits);
                            if($user->tuning_credits < $totalFilecredits){
                                $validator->errors()->add('user', __('customer.not_enough_credits'));
                            }
                        }
                    }
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
