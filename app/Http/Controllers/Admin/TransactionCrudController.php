<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\MasterController;
use App\Http\Requests\TransactionRequest as StoreRequest;
use App\Http\Requests\TransactionRequest as UpdateRequest;

/**
 * Class TransactionCrudController
 * @param App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TransactionCrudController extends MasterController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Transaction');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/transaction');
        $this->crud->setEntityNameStrings('transaction', 'transactions');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->removeButton('create');
        $this->crud->removeButton('update');
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');

        $this->crud->enableExportButtons();

        $user = \Auth::guard('admin')->user();
        $this->crud->query->whereHas('user', function($query) use($user){
            return $query->where('company_id', $user->company_id);
        });
        $this->crud->query->orderBy('id', 'DESC');

        /*
        |--------------------------------------------------------------------------
        | Basic Crud filter Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addFilter([
            'name' => 'business_name',
            'type' => 'select2',
            'label' => 'Company'
                ], function() use($user){
            return \App\User::customer()->where('company_id', $user->company_id)->distinct('business_name')->pluck('business_name', 'business_name')->toArray();
        }, function($value) {
            $this->crud->query->whereHas('user', function($query) use($value){
                return $query->where('business_name', 'LIKE', '%'.$value.'%');
            });
        });

        $this->crud->addFilter([
            'name' => 'status',
            'type' => 'dropdown',
            'label' => 'Status'
            ], config('site.transaction_status'), function($value) {
                $this->crud->addClause('WHERE', 'status', $value);
        });

//        $this->crud->addFilter([
//            'type' => 'date',
//            'name' => 'created_at',
//            'label' => 'Transaction Date'
//            ],
//            false,
//            function($value) {
//                $this->crud->query->whereDate('created_at', $value);
//        });

        $this->crud->addFilter([
            'type' => 'date_range',
            'name' => 'created_at',
            'label' => 'From/To Date'
            ],
            false,
            function($value) {
                $dates = json_decode($value);
                $this->crud->query->whereDate('created_at','>=', $dates->from);
                $this->crud->query->whereDate('created_at','<=', $dates->to);
        });

        /*
        |--------------------------------------------------------------------------
        | Basic Crud column Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
            'name' => 'description',
            'label' => __('customer_msg.tb_header_Description'),
        ]);

        $this->crud->addColumn([
            'name' => 'credits_with_type',
            'label' => __('customer_msg.tb_header_Credits'),
        ]);

        $this->crud->addColumn([
            'name' => 'status',
            'label' => __('customer_msg.tb_header_Status'),
        ]);

        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => __('customer_msg.tb_header_Date'),
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
        return $redirect_location;
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
