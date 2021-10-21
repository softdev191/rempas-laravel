<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\MasterController;
use App\Models\CustomerRating;
use App\Models\Company;
use Illuminate\Http\Request;
class DashboardController extends MasterController
{

    public function __construct(){
        parent::__construct();
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
		$customerRating = CustomerRating::where(['user_id'=>$this->user->id,'company_id'=>$this->user->company_id])->first();

        try{
			$data['customerRating']  = $customerRating;
            $data['title'] = trans('backpack::base.dashboard');
            $data['fileServices'] = $this->user->fileServices()->orderBy('id', 'DESC')->take(5)->get();
            $data['openFileServices'] = $this->user->fileServices()->where('status', 'O')->count();
            $data['waitingFileServices'] = $this->user->fileServices()->where('status', 'W')->count();
            $data['complatedFileServices'] = $this->user->fileServices()->where('status', 'C')->count();
            $data['resellerId'] = $this->user->reseller_id;

            $company = $this->user->company;
            $day = lcfirst(date('l'));
            $daymark_from = substr($day, 0, 3).'_from';
            $daymark_to = substr($day, 0, 3).'_to';
            $open_status = -1;
            if ($company->open_check) {
                if ($company->$daymark_from && str_replace(':', '', $company->$daymark_from) > date('Hi')
                    || $company->$daymark_to && str_replace(':', '', $company->$daymark_to) < date('Hi')) {
                    $open_status = $company->notify_check == 0 ? 1 : 2;
                }
            }
            $data['openStatus'] = $open_status;

            $url = "https://evc.de/services/api_resellercredits.asp";
            $dataArray = array(
                'apiid'=>'j34sbc93hb90',
                'username'=> $this->user->company->reseller_id,
                'password'=> $this->user->company->reseller_password,
                'verb'=>'getcustomeraccount',
                'customer' => $this->user->reseller_id
            );
            $ch = curl_init();
            $params = http_build_query($dataArray);
            $getUrl = $url."?".$params;
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $getUrl);
            curl_setopt($ch, CURLOPT_TIMEOUT, 500);

            $response = curl_exec($ch);
            if (strpos($response, 'ok') !== FALSE) {
                $data['evcCount'] = str_replace('ok: ', '', $response);
            }

            return view('backpack::dashboard', $data);
        }catch(\Exception $e){
            return abort(404);
        }
    }

	public function addRating(Request $request){
		if(isset($request->id) && !empty($request->id) ){
			$id = $request->id;
			$model = CustomerRating::where(['id'=>$id])->first();
		}else{
			$model = new CustomerRating();
		}
		$model->rating = $request->rating;
		$model->user_id = $this->user->id;
		$model->company_id = $this->user->company_id;
		$model->save();

		$avgRating = $model::where('company_id',$model->company_id)->avg('rating');

		$company = Company::find($model->company_id);
		$company->rating = $avgRating;
		$company->save();
		return redirect(backpack_url('dashboard'))->with('Rating Added');
    }

    public function setReseller(Request $request) {
        if ($request->reseller_id) {
            $url = "https://evc.de/services/api_resellercredits.asp";
            $dataArray = array(
                'apiid'=>'j34sbc93hb90',
                'username'=> $this->user->company->reseller_id,
                'password'=> $this->user->company->reseller_password,
                'verb'=>'addcustomer',
                'customer' => $request->reseller_id
            );
            $ch = curl_init();
            $data = http_build_query($dataArray);
            $getUrl = $url."?".$data;
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $getUrl);
            curl_setopt($ch, CURLOPT_TIMEOUT, 500);

            $response = curl_exec($ch);
            if (strpos($response, 'customer added') !== FALSE || strpos($response, 'customer already exists') !== FALSE) {
                $this->user->reseller_id = $request->reseller_id;
                $this->user->save();
            } else {
                \Alert::error(__('customer.opps').'\r\n'.$response)->flash();
            }
        } else {
            $this->user->reseller_id = '';
            $this->user->save();
        }

        return redirect(backpack_url('dashboard'))->with('Reseller Set');
    }
}
