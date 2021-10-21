<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\MasterController;
use App\Http\Requests\OrderRequest as StoreRequest;
use App\Http\Requests\OrderRequest as UpdateRequest;
use Dompdf\Dompdf;

/**
 * Class OrderCrudController
 * @param App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OrderCrudController extends MasterController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Order');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/order');
        $this->crud->setEntityNameStrings('order', 'orders');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->addButtonFromView('line', 'invoice', 'invoice' , 'end');
        $this->crud->removeButton('create');
        $this->crud->removeButton('delete');
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('update');
        $this->crud->enableExportButtons();
        $this->crud->setEditView('vendor.custom.common.order.index');

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
            ], config('site.order_status'), function($value) {
                $this->crud->addClause('WHERE', 'status', $value);
        });

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
            'name' => 'created_at',
            'label' => __('customer_msg.tb_header_OrderDate'),
        ]);

        $this->crud->addColumn([
            'name' => 'customer_company',
            'label' => __('customer_msg.tb_header_Company'),
        ]);

        $this->crud->addColumn([
            'name' => 'amount_with_sign',
            'label' => __('customer_msg.tb_header_Amount'),
        ]);

        $this->crud->addColumn([
            'name' => 'status',
            'label' => __('customer_msg.tb_header_Status'),
        ]);

        $this->crud->addColumn([
            'name' => 'displayable_id',
            'label' => __('customer_msg.tb_header_InvoiceNo'),
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

    /**
     * download invoice
     * @param \App\Models\Order $order
     * @return $response
     */
    public function invoice(\App\Models\Order $order){
        try{
            $pdf = new Dompdf;
            $invoiceName = 'invoice_'.$order->displayable_id.'.pdf';

            $pdf->loadHtml(
                view('vendor.custom.common.invoice')->with(['order'=>$order, 'company'=>$this->company])->render()
            );
            $pdf->setPaper('A4', 'landscape');
            $pdf->render();
            return $pdf->stream($invoiceName);
        }catch(\Exception $e){
            \Alert::error(__('admin.opps'))->flash();
            return redirect(url('admin/order'));
        }
    }
}
