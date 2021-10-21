<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Car;
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

    public function browser() {
        $company  = $this->company;
        $brands = Car::groupBy('brand')->pluck('brand');

        return View::make("auth.browser", compact('company', 'brands'));
    }

    public function browserResult(Request $request) {
        $company  = $this->company;
        $car = Car::find($request->id);
        // dd($car);
        $logofile = str_replace(" ", "-", strtolower($car->brand));
        $logofile = asset('images/carlogo/'.$logofile.'.jpg');

        return View::make('auth.browser_result', compact('company', 'car', 'logofile'));
    }

    public function browserCategory() {
        $make = request()->make;
        $model = request()->model;
        $generation = request()->generation;
        $engine = request()->engine;
        if ($engine) {
            $car = $engines = Car::find($engine);
            $logofile = str_replace(" ", "-", strtolower($car->brand));
            $logofile = asset('images/carlogo/'.$logofile.'.jpg');
            return View::make('auth.browser_result', compact('car', 'logofile'));
        } else if ($generation) {
            $engines = Car::where('brand', $make)
                ->where('model', $model)
                ->where('year', $generation)
                ->get();
            $logo = asset('images/carlogo/'.str_replace(" ", "-", strtolower($make)).'.jpg');
            return View::make('auth.browser_category')
                ->with('mode', 'generation')
                ->with('subitems', $engines)
                ->with('title', $generation)
                ->with('brand', $make)
                ->with('model', $model)
                ->with('logo', $logo);
        } else if ($model) {
            $generations = Car::where('brand', $make)
                ->where('model', $model)
                ->groupBy('year')
                ->pluck('year');
            $logo = asset('images/carlogo/'.str_replace(" ", "-", strtolower($make)).'.jpg');
            return View::make('auth.browser_category')
                ->with('mode', 'model')
                ->with('subitems', $generations)
                ->with('title', $model)
                ->with('brand', $make)
                ->with('logo', $logo);
        } else if ($make) {
            $models = Car::where('brand', $make)
                ->groupBy('model')
                ->pluck('model');
            $logo = asset('images/carlogo/'.str_replace(" ", "-", strtolower($make)).'.jpg');
            return View::make('auth.browser_category')
                ->with('mode', 'make')
                ->with('subitems', $models)
                ->with('title', $make)
                ->with('logo', $logo);
        } else {
            $res = array();
            $brands = Car::groupBy('brand')->pluck('brand');
            foreach($brands as $i => $b) {
                array_push($res, [
                    'brand' => $b,
                    'logo' => asset('images/carlogo/'.str_replace(" ", "-", strtolower($b)).'.jpg')
                ]);
            }
            return View::make('auth.browser_brands')->with('brands', $res);
        }
    }

	function create(Request $request){
		$company  = $this->company;

        $request->company_id = $company->id;

		$email = $request->email;

		$validatedData = $request->validate([
                'private' => 'required',
				'first_name' => 'required|max:255',
				'last_name' => 'required|max:255',
				'business_name' =>  'required|max:255',
				'email' => 'required|unique:users,email,NULL,id,company_id,'.$request->company_id,
				'address_line_1' =>  'required|max:255',
				'county' =>  'required|max:255',
				'town' =>  'required|max:255',
                'phone' =>  'required|max:255',
                'password' => 'required|string|min:8|confirmed',
			],
			[
				'email'=>[
					'required'=>'The email field is required.',
					'unique'=>'This email is already exicts.',

				]
			]
        );

		$model = new User();
        // $model->tuning_credit_group_id = request('tuning_credit_group_id');
        $model->private = request('private');
        $model->vat_number = request('vat_number');
        $model->title = request('title');
        $model->first_name = request('first_name');
        $model->last_name =request('last_name');
        $model->lang ='en';
        $model->email =request('email');
        $model->business_name =request('business_name');
        $model->address_line_1 =request('address_line_1');
        $model->address_line_2 =request('address_line_2');
        $model->post_code =request('post_code');
        $model->county =request('county');
        $model->town =request('town');
        $model->phone =request('phone');
        $model->tools =request('tools');
        $model->password = Hash::make(request('password'));
        $model->company_id = $company->id;

		$model->save();
		return redirect()->route('users_registers')->with(['message'=>'Registration has been saved successfully.','status'=>'success']);
	}


}
