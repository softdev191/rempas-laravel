<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use View;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/customer/dashboard';
    protected $company;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:customer');
        $this->company = \App\Models\Company::where('domain_link', url(''))->first();
        if(!$this->company){
            abort(400, 'No such domain('.url("").') is registerd with system. Please contact to webmaster.');
        }
        view::share('company', $this->company);
    }

    /**
     * Reset the guard.
     *
     * @return \Illuminate\Http\Response
     */
    protected function guard()
    {
        return Auth::guard('customer');
    }

    /**
     * Reset password broker.
     *
     * @return \Illuminate\Http\Response
     */
    public function broker() {

        return Password::broker('customers');
    }
}
