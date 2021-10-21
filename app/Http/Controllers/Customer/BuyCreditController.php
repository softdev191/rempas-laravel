<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\MasterController;

class BuyCreditController extends MasterController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * show credit options.
     */
    public function index()
    {
        try{
            $data['title'] = "buy credit";
            $data['tuningCreditGroup'] = $this->user->tuningCreditGroup;
            $data['tuningEVCCreditGroup'] = $this->user->tuningEVCCreditGroup;
            $data['groupCreditTires']  = $this->user->tuningCreditGroup
                ? $this->user->tuningCreditGroup->tuningCreditTires()->withPivot('from_credit', 'for_credit')->wherePivot('from_credit', '!=', 0.00)->orderBy('amount', 'ASC')->get()
                : [];
            if ($this->user->tuningEVCCreditGroup)
                $data['groupEVCCreditTires']  = $this->user->tuningEVCCreditGroup
                    ? $this->user->tuningEVCCreditGroup->tuningCreditTires()->withPivot('from_credit', 'for_credit')->wherePivot('from_credit', '!=', 0.00)->orderBy('amount', 'ASC')->get()
                    : [];
            else
                $data['groupEVCCreditTires']  = [];
            $data['stripeKey'] = $this->user->company->stripe_key;
            return view('vendor.custom.customer.buy_credit.index', $data);
        }catch(\Exception $e){
            \Alert::error(__('customer.opps'))->flash();
            return redirect(url('customer/dashboard'));
        }
    }

}
