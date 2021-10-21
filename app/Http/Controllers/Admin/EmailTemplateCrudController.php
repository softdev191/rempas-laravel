<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EmailTemplateRequest as StoreRequest;
use App\Http\Requests\EmailTemplateRequest as UpdateRequest;
use App\Http\Controllers\MasterController;

/**
 * Class EmailTemplateCrudController
 * @param App\Http\Controllers\Admin
 * @return CrudPanel $crud
 */
class EmailTemplateCrudController extends MasterController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\EmailTemplate');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/email-template');
        $this->crud->setEntityNameStrings('email template', 'email templates');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->removeButton('create');
        $this->crud->removeButton('delete');
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('delete');

        $user = \Auth::guard('admin')->user();
        $this->crud->query->where('company_id', $user->company_id);
        $this->crud->query->orderBy('id', 'DESC');

        /*
        |--------------------------------------------------------------------------
        | Basic Crud column Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
            'name' => 'email_type',
            'label' => __('customer_msg.tb_header_EmailType'),
        ]);

        $this->crud->addColumn([
            'name' => 'subject',
            'label' => __('customer_msg.tb_header_Subject'),
        ]);

        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => __('customer_msg.tb_header_ModifiedAt'),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Basic Crud Field Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addField([
            'name' => 'label',
            'label' => "Label",
            'type' => 'select_from_array',
            'options' => config('site.emailes'),
            'allows_null' => false,
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);

        $this->crud->addField([
            'name' => 'subject',
            'label' => "Subject",
            'type' => 'text',
            'attributes'=>['placeholder'=>'Subject'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);

        $this->crud->addField([
            'name' => 'body',
            'label' => "Body",
            'type' => 'wysiwyg'
        ]);

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    /**
     * Store resource
     * @package App\Http\Request\StoreRequest $request
     * @return $response
     */
    public function store(StoreRequest $request)
    {
        $request->request->add(['company_id'=> $this->company->id]);
        $redirect_location = parent::storeCrud($request);
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
}
