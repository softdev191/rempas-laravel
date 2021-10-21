<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\MasterController;
use App\Http\Requests\TuningTypeRequest as StoreRequest;
use App\Http\Requests\TuningTypeRequest as UpdateRequest;

/**
 * Class TuningTypeCrudController
 * @param App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TuningTypeCrudController extends MasterController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\TuningType');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/tuning-type');
        $this->crud->setEntityNameStrings('tuning type', 'tuning types');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->addButtonFromView('line', 'up_grade_order', 'up_grade_order', 'end');
        $this->crud->addButtonFromView('line', 'down_grade_order', 'down_grade_order' , 'end');
        $this->crud->enableExportButtons();

        $user = \Auth::guard('admin')->user();
        $this->crud->query->where('company_id', $user->company_id);
        $this->crud->query->orderBy('order_as', 'ASC');

        /*
        |--------------------------------------------------------------------------
        | Basic Crud column Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
            'name' => 'label',
            'label' => __('customer_msg.tb_header_Label'),
        ]);

        $this->crud->addColumn([
            'name' => 'credits',
            'label' => __('customer_msg.tb_header_Credit'),
        ]);

        $this->crud->addColumn([
            'label' => __('customer_msg.tb_header_TuningOptions'),
            'type' => "model_function",
            'function_name' => 'getTuningOptionsWithLink',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Basic Crud Field Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addField([
            'name' => 'label',
            'label' => "Label",
            'type' => 'text',
            'attributes'=>['placeholder'=>'Label'],
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
        $request->request->add(['company_id'=> $this->company->id]);
        $redirect_location = parent::storeCrud($request);
        $tuningType = $this->crud->entry;
        //$tuningType->order_as = $tuningType->id;
		$tuningType->order_as = \App\Models\TuningType::where('company_id', $this->company->id)->count();
        $tuningType->save();
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

        if($this->company->id != $entry->company->id){
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
     * @return $response
     */
    public function upGradeOrder(\App\Models\TuningType $tuningType)
    {
        try{
            $current = $tuningType;
            $previous = \App\Models\TuningType::where('company_id', $this->company->id)->where('order_as', '<', $current->order_as)->orderBy('order_as', 'DESC')->first();
            if($previous){
                //dd($previous);
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
        return redirect(url('admin/tuning-type'));
    }

    /**
     * Downgrade order
     * @param \App\Models\TuningType $tuningType
     * @return $response
     */
    public function downGradeOrder(\App\Models\TuningType $tuningType)
    {
        try{
            $current = $tuningType;
            $next = \App\Models\TuningType::where('company_id', $this->company->id)->where('order_as', '>', $current->order_as)->orderBy('order_as', 'ASC')->first();
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
        return redirect(url('admin/tuning-type'));
    }
}
