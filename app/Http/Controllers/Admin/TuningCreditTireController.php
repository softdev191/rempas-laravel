<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TuningCreditTireRequest as CreditTireRequest;
use App\Http\Controllers\MasterController;
use App\Models\TuningCreditTire;

class TuningCreditTireController extends MasterController
{

    /**
     * show tuning credit tire form
     * @param App\Http\Requests\TuningCreditTireRequest $request
     * @return $response
     */
    public function creditTire(CreditTireRequest $request){
        $data['title'] = "Tuning credit tire";
        return view('vendor.custom.common.tuning_credit.add_credit_tire', compact('data'));
    }

    /**
     * update tuning credit tire
     * @param App\Http\Requests\TuningCreditTireRequest $request
     * @return $response
     */
    public function updateCreditTire(CreditTireRequest $request){
        try{
            $request->request->add(['company_id'=> $this->company->id, 'group_type' => 'normal']);
            $tuningCreditTire = new TuningCreditTire($request->all());

            if($tuningCreditTire->save()){
                \Alert::success(__('admin.credit_tire_saved'))->flash();
            }else{
                \Alert::error(__('admin.opps'))->flash();
            }
        }catch(\Exception $e){
            \Alert::error(__('admin.opps'))->flash();
        }
        return redirect(url('admin/tuning-credit'));
    }

    /**
     * delete tuning credit tire
     * @param TuningCreditTire $tuningCreditTire
     * @return $response
     */
    public function deleteCreditTire(TuningCreditTire $tuningCreditTire){
        try{
            if($tuningCreditTire->delete()){

            \Alert::success(__('admin.credit_tire_deleted'))->flash();
            }else{
                \Alert::error(__('admin.opps'))->flash();
            }
        }catch(\Exception $e){
            \Alert::error(__('admin.opps'))->flash();
        }
        return redirect(url('admin/tuning-credit'));
    }
}
