<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\MasterController;
use App\Http\Requests\TuningTypeOptionRequest as StoreRequest;
use App\Http\Requests\TuningTypeOptionRequest as UpdateRequest;

/**
 * Class TuningTypeOptionCrudController
 * @param App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TuningTypeOptionCrudController extends MasterController
{
    public function setup()
    {

        $tuningTypeId = \Route::current()->parameter('tuningType');
        $tuningType = \App\Models\TuningType::find($tuningTypeId);
        if(!$tuningType){
            \Alert::error(__('admin.select_tuning_type'))->flash();
        }
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\TuningTypeOption');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/tuning-type/'.$tuningTypeId.'/options');
        $this->crud->setEntityNameStrings('tuning type option', 'Tuning options for '.@$tuningType->label);

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addButtonFromView('top', 'back_to_tuning_type', 'back_to_tuning_type' , 'beginning');
        $this->crud->addButtonFromView('line', 'up_grade_order', 'up_grade_order', 'end');
        $this->crud->addButtonFromView('line', 'down_grade_order', 'down_grade_order' , 'end');
        $this->crud->enableExportButtons();
        
        $user = \Auth::guard('admin')->user();
        $this->crud->query->where('tuning_type_id', $tuningTypeId);
        $this->crud->query->whereHas('tuningType', function($query) use($user){
            return $query->where('company_id', $user->company_id);
        });

        $this->crud->query->orderBy('order_as', 'ASC');

        /*
        |--------------------------------------------------------------------------
        | Basic Crud column Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
            'name' => 'label',
            'label' => 'Label',
        ]);


        $this->crud->addColumn([
            'name' => 'credits',
            'label' => 'Credits',
        ]);

        $this->crud->addColumn([
            'name' => 'tooltip',
            'label' => 'Tooltip',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Basic Crud Field Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addField([
            'name' => 'tuning_type_id',
            'type' => 'hidden',
            'value' => $tuningTypeId
        ]);

        $this->crud->addField([
            'name' => 'label',
            'label' => "Label",
            'type' => 'text',
            'attributes'=>['placeholder'=>'Label'],
            'wrapperAttributes'=>['class'=>'form-group col-md-4 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank1',
            'type' => 'blank',
        ]);

        $this->crud->addField([
            'name' => 'tooltip',
            'label' => "Tooltip <small class='text-muted'>(optional)</small>",
            'type' => 'text',
            'attributes'=>['placeholder'=>'Tooltip'],
            'wrapperAttributes'=>['class'=>'form-group col-md-4 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank2',
            'type' => 'blank',
        ]);

        $this->crud->addField([
            'name' => 'credits',
            'label' => "Credits",
            'type' => 'text',
            'attributes'=>['placeholder'=>'Credits'],
            'wrapperAttributes'=>['class'=>'form-group col-md-4 col-xs-12']
        ]);

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    /**
     * Store resource
     * @param App\Http\Request\StoreRequest $request
     * @return $response
     */
    public function store(StoreRequest $request)
    {
        $redirect_location = parent::storeCrud($request);
        $tuningTypeOption = $this->crud->entry;
        //$tuningTypeOption->order_as = $tuningTypeOption->id;
		$tuningTypeOption->order_as = \App\Models\TuningTypeOption::where('tuning_type_id',$tuningTypeOption->tuning_type_id)->count();
        $tuningTypeOption->save();
        return $redirect_location;
    }

    /**
     * Edit resource
     * @param (int) $id
     * @return $response
     */
    public function edit($id){

        $id = $this->crud->getCurrentEntryId() ?? $id;
        $entry = $this->crud->getEntry($id);
        
        if($this->company->id != $entry->tuningType->company->id){
            abort(403, __('admin.no_permission'));
        }

        $data['entry'] = $entry;
        $data['crud'] = $this->crud;
        $data['saveAction'] = $this->getSaveAction();
        $data['fields'] = $this->crud->getUpdateFields($id);
        $data['title'] = trans('backpack::crud.edit').' '.$this->crud->entity_name;
        $data['id'] = $id;

        return view($this->crud->getEditView(), $data);
    }

    /**
     * Update resource
     * @param App\Http\Request\UpdateRequest $request
     * @return $response
     */
    public function update(UpdateRequest $request)
    {
        $redirect_location = parent::updateCrud($request);
        return $redirect_location;
    }

    /**
     * Upgrade order
     * @param \App\Models\TuningType $tuningType
     * @param \App\Models\TuningTypeOption $tuningTypeOption
     * @return $response
     */
    public function upGradeOrder($tuningType, \App\Models\TuningTypeOption $tuningTypeOption)
    {
        try{
            $current = $tuningTypeOption;
            $previous = \App\Models\TuningTypeOption::where('tuning_type_id', $current->tuning_type_id)->where('order_as', '<', $current->order_as)->orderBy('order_as', 'DESC')->first();
            if($previous){
                $currentOrder = $current->order_as;
                $previousOrder = $previous->order_as;
                $current->order_as = $previousOrder;
                $current->save();
                $previous->order_as = $currentOrder;
                $previous->save();
            }
        }catch(\Exception $e){
            \Alert::error(__('admin.opps'))->flash();
        }
        return redirect()->back();
    }


    /**
     * Downgrade order
     * @param \App\Models\TuningType $tuningType
     * @param \App\Models\TuningTypeOption $tuningTypeOption
     * @return $response
     */
    public function downGradeOrder($tuningType, \App\Models\TuningTypeOption $tuningTypeOption)
    {
        try{
            $current = $tuningTypeOption;
			//dd($current);
            $next = \App\Models\TuningTypeOption::where('tuning_type_id', $current->tuning_type_id)->where('order_as', '>', $current->order_as)->orderBy('order_as', 'ASC')->first();
            if($next){
                $currentOrder = $current->order_as;
                $nextOrder = $next->order_as;
                $current->order_as = $nextOrder;
                $current->save();
                $next->order_as = $currentOrder;
                $next->save();
            }
        }catch(\Exception $e){
            \Alert::error(__('admin.opps'))->flash();
        }
        return redirect()->back();
    }
}
