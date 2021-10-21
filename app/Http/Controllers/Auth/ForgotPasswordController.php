<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use View;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

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
     * Handle a send reset link email request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */

    public function sendResetLinkEmail(Request $request)
    {
        
        $this->validate($request, ['email' => 'required|email']);

        $user_check = \App\User::where('email', $request->email)->first();
        if($user_check){
            if($user_check->is_admin == 1){
                return redirect()->back()->with(['status'=>'error', 'message'=>__('auth.invalid_customer_privilege')]);
            }

            if($user_check->company_id != $this->company->id){
                return redirect()->back()->with(['status'=>'error', 'message'=>__('auth.invalid_domain')]);
            }
        }
        $response = $this->broker()->sendResetLink(
                    $request->only('email')
                );

        if ($response === Password::RESET_LINK_SENT) {
            return back()->with(['status'=>'success', 'message'=>trans($response)]);
        }

        return back()->withErrors(
            ['email' => trans($response)]
        );
    }

    /**
     * Reset password broker.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function broker() {

        return Password::broker('customers');
    }
}
