<?php

namespace App\Http\Controllers\Customer;

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
    public function __construct(){
        parent::__construct();

    }

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Order');
        $this->crud->setRoute('customer/order');
        $this->crud->setEntityNameStrings('order', __('customer_msg.menu_Orders'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->addButtonFromView('line', 'invoice', 'invoice' , 'end');
        $this->crud->removeButton('create');
        $this->crud->removeButton('delete');
        $this->crud->removeButton('update');
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('update');
        $this->crud->enableExportButtons();

        $this->crud->query->where('user_id', \Auth::guard('customer')->user()->id);
        $this->crud->query->orderBy('id', 'DESC');

        /*
        |--------------------------------------------------------------------------
        | Basic Crud filter Configuration
        |--------------------------------------------------------------------------
        */


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
            'name' => 'displayable_id',
            'label' => __('customer_msg.title_OrderNo'),
        ]);

        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => __('customer_msg.tb_header_Date'),
        ]);

        $this->crud->addColumn([
            'name' => 'description',
            'label' => __('customer_msg.tb_header_Description'),
        ]);

        $this->crud->addColumn([
            'name' => 'amount_with_sign',
            'label' => __('customer_msg.tb_header_Amount'),
        ]);

        $this->crud->addColumn([
            'name' => 'status',
            'label' => __('customer_msg.tb_header_Status'),
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
            \Alert::error(__('customer.opps'))->flash();
            return redirect(url('customer/order'));
        }
    }
}
