<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\MasterController;
use App\Http\Requests\SubscriptionPaymentRequest as StoreRequest;
use App\Http\Requests\SubscriptionPaymentRequest as UpdateRequest;
use Dompdf\Dompdf;

/**
 * Class SubscriptionPaymentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SubscriptionPaymentCrudController extends MasterController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SubscriptionPayment');
        $this->crud->setRoute('admin/subscription-payment');
        $this->crud->setEntityNameStrings('subscription payment', 'subscription payments');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->addButtonFromView('line', 'Invoice', 'subscription_invoice' , 'end');
        $this->crud->addButtonFromView('top', 'back_to_subscriptions', 'back_to_subscriptions' , 'beginning');
        $this->crud->removeButton('create');
        //$this->crud->removeAllButtonsFromStack('line');
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('update');

        $user = \Auth::guard('admin')->user();
        $company = \Request::query('company');
        $subscription = \Request::query('subscription');
        if($user->is_master){
            if($company){
                $this->crud->query->whereHas('subscription', function($query) use($subscription){
                    $query->where('id', $subscription);
                });
            }
        }else{
            $this->crud->query->whereHas('subscription', function($query) use($subscription, $user){
                $query->where('id', $subscription)->where('user_id', $user->id);
            });
        }


        $this->crud->query->orderBy('id', 'DESC');

        /*
        |--------------------------------------------------------------------------
        | Basic Crud column Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
            'name' => 'next_billing_date',
            'label' => __('customer_msg.tb_header_NextBillingDate'),
        ]);

        $this->crud->addColumn([
            'name' => 'last_payment_date',
            'label' => __('customer_msg.tb_header_LastPaymentDate'),
        ]);

        $this->crud->addColumn([
            'name' => 'last_payment_amount',
            'label' => __('customer_msg.tb_header_LastPaymentAmount'),
        ]);

        $this->crud->addColumn([
            'name' => 'failed_payment_count',
            'label' => __('customer_msg.tb_header_FailedPaymentCount'),
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
     * @param \App\Models\SubscriptionPayment $subscription_payment
     * @return $response
     */
    public function invoice(\App\Models\SubscriptionPayment $subscription_payment){

        try{
            $subscription_payment = $subscription_payment->first();
            $company = $this->company;
            $pdf = new Dompdf;
            $invoiceName = 'invoice_'.$subscription_payment->id.'.pdf';
            $pdf->loadHtml(
                view('vendor.custom.common.subscription_invoice')->with(['subscription_payment'=>$subscription_payment, 'company'=>$this->company, 'user'=>$this->user])->render()
            );
            $pdf->setPaper('A4', 'landscape');
            $pdf->render();
            return $pdf->stream($invoiceName);
        }catch(\Exception $e){

            \Alert::error(__('admin.opps'))->flash();
            return redirect(url('admin/subscription-payment?company='.$company->id.'&subscription='.$subscription_payment->subscription_id));
        }
    }
}
