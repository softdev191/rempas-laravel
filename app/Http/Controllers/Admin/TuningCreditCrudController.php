<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TuningCreditGroupRequest as StoreRequest;
use App\Http\Requests\TuningCreditGroupRequest as UpdateRequest;
use App\Http\Controllers\MasterController;
use Illuminate\Http\Request;
/**
 * Class TuningCreditCrudController
 * @param App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TuningCreditCrudController extends MasterController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\TuningCreditGroup');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/tuning-credit');
        $this->crud->setEntityNameStrings('tuning credit group', 'tuning credits');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addButtonFromView('top', 'add_credit_tire', 'add_credit_tire' , 'end');
        $this->crud->addButtonFromView('line', 'default', 'default' , 'end');
        $this->crud->setEditView('vendor.custom.common.settings.edit_credit_price_group');
        $this->crud->setCreateView('vendor.custom.common.settings.create_credit_price_group');
		$this->crud->setListView('vendor.custom.common.settings.list_credit_price_group');
        $this->crud->enableExportButtons();

        $user = \Auth::guard('admin')->user();
        $this->crud->query->where('company_id', $user->company_id)->where('group_type', 'normal');
        $this->crud->query->orderBy('id', 'DESC');

        $tuningCreditTires = \App\Models\TuningCreditTire::where('company_id', $user->company_id)->where('group_type', 'normal')->orderBy('amount', 'ASC')->get();

        /*
        |--------------------------------------------------------------------------
        | Basic Crud column Configuration
        |--------------------------------------------------------------------------
        */

		//changes
			$this->crud->addColumn([
				'name' => 'set_default_tier',
				'label' => __('customer_msg.tb_header_SetDefault'),
				'type' => "model_function",
				'function_name' => 'set_default_tier',

			]);

        $this->crud->addColumn([
            'name' => 'name',
            'label' => __('customer_msg.tb_header_Group'),
        ]);

        if($tuningCreditTires->count() > 0){
            foreach($tuningCreditTires->take(5) as $tuningCreditTire){
                $this->crud->addColumn([
                    'name' => $tuningCreditTire->amount.'_credit',
                    'label' => $tuningCreditTire->amount.' '.__('customer_msg.tb_header_Credit'),
                    'type' => "group_credit_tire_price",
                    'credit_tire' => $tuningCreditTire
                ]);
            }
        }
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        // dd($tuningCreditTires);
    }

	//changes
	public function set_default_tier(Request $request){
		$id = $request->id;

		$tuningCreditTires = \App\Models\TuningCreditGroup::find($id);

		$company_id = $tuningCreditTires->company_id;
		\App\Models\TuningCreditGroup::where('company_id', $company_id)->where('group_type', 'normal')->update(['set_default_tier' => '0']);


		$tuningCreditTires->set_default_tier =1;
		$tuningCreditTires->save(['set_default_tier' => '1']);
		return 1;
	}

    /**
     * Store resource
     * @param App\Http\Request\StoreRequest $request
     * @return $response
     */
    public function store(StoreRequest $request)
    {
        $request->request->add(['company_id'=> $this->company->id]);
        $request->request->add(['group_type'=> 'normal']);
        $redirect_location = parent::storeCrud($request);
        $tuningCreditGroup = $this->crud->entry;

        if($request->has('credit_tires')){
            $tuningCreditGroup->tuningCreditTires()->sync($request->credit_tires);
        }

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
     * @param \App\Models\Order $order
     * @return $response
     */
    public function update(UpdateRequest $request)
    {
        $redirect_location = parent::updateCrud($request);
        $tuningCreditGroup = $this->crud->entry;

        if($request->has('credit_tires')){
            $tuningCreditGroup->tuningCreditTires()->sync($request->credit_tires);
        }
        return $redirect_location;
    }

    /**
     * mark as default resource
     * @param \App\Models\TuningCreditGroup $tuningCreditGroup
     * @return $response
     */
    public function markDefault(\App\Models\TuningCreditGroup $tuningCreditGroup){
        try{
            $tuningCreditGroup->is_default = 1;
            if($tuningCreditGroup->save()){
                \App\Models\TuningCreditGroup::where('id', '!=', $tuningCreditGroup->id)->update(['is_default'=> 0]);
                \Alert::success(__('admin.default_success'))->flash();
            }else{
                \Alert::error(__('admin.opps'))->flash();
            }
        }catch(\Exception $e){
            \Alert::error(__('admin.opps'))->flash();
        }
        return redirect(backpack_url('tuning-credit'));
    }
}
