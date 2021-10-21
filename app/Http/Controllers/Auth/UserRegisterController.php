<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use View;

class UserRegisterController extends Controller
{
	
	public function __construct()
    {
        $this->middleware('guest:customer')->except('logout');
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';
		$host = $protocol.request()->getHttpHost();
		$this->company = \App\Models\Company::where('domain_link', $host)->first();
        if(!$this->company){
            abort(400, 'No such domain('.url("").') is registerd with system. Please contact to webmaster.');
        }
        view::share('company', $this->company);
    }
	
	public function register(){
		$company  = $this->company;
		$tuningpricetype =  \App\Models\TuningCreditGroup::where('company_id', $company->id)->orderBy('is_default', 'DESC')->pluck('name', 'id');
		
		return View::make("auth.user_register", compact('company','tuningpricetype'));
	}
	
	function create(Request $request){
		$company  = $this->company;
		
		$request->company_id = $company->id;
		
		$email = $request->email;
		
		$validatedData = $request->validate([
				'first_name' => 'required|max:255',
				'last_name' => 'required|max:255',
				'business_name' =>  'required|max:255',
				'email' => 'required|unique:users,email,NULL,id,company_id,'.$request->company_id,
				'address_line_1' =>  'required|max:255',
				'county' =>  'required|max:255',
				'town' =>  'required|max:255',
				'phone' =>  'required|max:255',
			],
			[
				'email'=>[
					'required'=>'The email field is required.',
					'unique'=>'This email is already exicts.',
					
				]
			]
		);
		
		$model = new User();
        $model->tuning_credit_group_id = request('tuning_credit_group_id');
        $model->title = request('title');
        $model->first_name = request('first_name');
        $model->last_name =request('last_name');
        $model->lang =request('lang');
        $model->email =request('email');
        $model->business_name =request('business_name');
        $model->address_line_1 =request('address_line_1');
        $model->address_line_2 =request('address_line_2');
        $model->post_code =request('post_code');
        $model->county =request('county');
        $model->town =request('town');
        $model->phone =request('phone');
        $model->tools =request('tools');
        $model->company_id = $company->id;
        
		$model->save();
		return redirect()->route('users_registers')->with(['message'=>'Registration has been saved successfully.','status'=>'success']);
	}
	
	
}