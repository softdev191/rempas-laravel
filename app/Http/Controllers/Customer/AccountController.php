<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\MasterController;
use App\Http\Requests\AccountInfoRequest;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Hash;
use Auth;

class AccountController extends MasterController
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
     * Show the user a form to change his personal information.
     */
    public function getAccountInfoForm()
    {
        try{
            $data['title'] = trans('backpack::base.my_account');
            return view('backpack::auth.account.update_info', compact('data'));
        }catch(\Exception $e){
            \Alert::error(__('customer.opps'))->flash();
            return redirect(url('customer/dashboard'));
        }
    }

    /**
     * Save the modified personal information for a user.
     * @param AccountInfoRequest $request
     */
    public function postAccountInfoForm(AccountInfoRequest $request)
    {
        try{
            $result = $this->user->update($request->except(['_token']));

            if ($result) {
                \Alert::success(trans('backpack::base.account_updated'))->flash();
            } else {
                \Alert::error(trans('backpack::base.error_saving'))->flash();
            }
        }catch(\Exception $e){
            \Alert::error(__('customer.opps'))->flash();
        }

        return redirect()->back();
    }

    /**
     * Show the user a form to change his login password.
     */
    public function getChangePasswordForm()
    {
        try{
            $data['title'] = trans('backpack::base.my_account');
            return view('backpack::auth.account.change_password', compact('data'));
        }catch(\Exception $e){
            \Alert::error(__('customer.opps'))->flash();
            return redirect(url('customer/dashboard'));
        }
    }

    /**
     * Save the new password for a user.
     * @param ChangePasswordRequest $request
     */
    public function postChangePasswordForm(ChangePasswordRequest $request)
    {

        try{
            $user = $this->user;
            $user->password = Hash::make($request->new_password);

            if ($user->save()) {
                \Alert::success(trans('backpack::base.account_updated'))->flash();
            } else {
                \Alert::error(trans('backpack::base.error_saving'))->flash();
            }
        }catch(\Exception $e){
            \Alert::error(__('customer.opps'))->flash();
        }
        return redirect()->back();
    }

}
