<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\MasterController;
use App\Http\Requests\SubscriptionRequest as StoreRequest;
use App\Http\Requests\SubscriptionRequest as UpdateRequest;
use PayPal\Auth\OAuthTokenCredential;
use Illuminate\Http\Request;
use PayPal\Rest\ApiContext;
use Config;
/**
 * Class SubscriptionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SubscriptionCrudController extends MasterController
{

    private $apiContext;

    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct(){

        parent::__construct();

        $company = \App\Models\Company::where('is_default', 1)->first();
        if($company){
            \Config::set('paypal.client_id', $company->paypal_client_id);
            \Config::set('paypal.secret', $company->paypal_secret);
        }
        $paypalConf = \Config::get('paypal');
		//$paypalConf['settings']['mode'] = 'sandbox';
        $this->apiContext = new ApiContext(new OAuthTokenCredential($paypalConf['client_id'], $paypalConf['secret']));
        $this->apiContext->setConfig($paypalConf['settings']);
    }

    /**
     * setup crud.
     * @return void
     */
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Subscription');
        $this->crud->setRoute('admin/subscription');
        $this->crud->setEntityNameStrings('subscription', 'subscriptions');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->removeButton('create');
        $this->crud->removeButton('update');
        $this->crud->removeButton('delete');
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('update');
        $this->crud->addButtonFromView('top', 'back_to_all_companies', 'back_to_all_companies' , 'beginning');
        $this->crud->addButtonFromView('line', 'subscription_payment', 'subscription_payment' , 'end');
        $this->crud->addButtonFromView('line', 'cancel_subscription', 'cancel_subscription' , 'end');
        $this->crud->addButtonFromView('line', 'cancel_subscription_immediate', 'cancel_subscription_immediate' , 'end');
        $this->crud->enableExportButtons();

        $user = \Auth::guard('admin')->user();
        $company = \Request::query('company');
        if($user->is_master){
            if($company){
                $this->crud->query->whereHas('user', function($query) use($company){
                    $query->where('company_id', $company);
                });
            }
        }else{
            $this->crud->query->where('user_id', $user->id);
        }
        $this->crud->query->orderBy('id', 'DESC');

        /*
        |--------------------------------------------------------------------------
        | Basic Crud column Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
            'name' => 'pay_agreement_id',
            'label' => __('customer_msg.tb_header_AggreeId'),
        ]);

        $this->crud->addColumn([
            'name' => 'description',
            'label' => __('customer_msg.tb_header_Description'),
        ]);

        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => __('customer_msg.tb_header_StartedAt'),
        ]);

        $this->crud->addColumn([
            'name' => 'next_billing_date',
            'label' => __('customer_msg.tb_header_NextBillingDate'),
            'type' => 'closure',
            'function' => function($entry) {
                if($entry->is_trial==1){
                    $trailStartDate = \Carbon\Carbon::parse($entry->start_date);
                    return $trailStartDate->addDays($entry->trial_days)->format('d M Y g:i A');
                }
                else{
                    return $entry->next_billing_date;
                }
            }
        ]);
        $this->crud->addColumn([
            'name' => 'is_trial',
            'label' => __('customer_msg.tb_header_Type'),
            'type' => 'boolean',
            'options' => [0 => 'Paid', 1 => 'Trial']
        ]);

        $this->crud->addColumn([
            'name' => 'status',
            'label' => __('customer_msg.tb_header_Status'),

        ]);

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    /**
     * Show subscription plans
     * @return \Illuminate\Http\Response
     */
    public function showSubscriptionPackages(){
        if(!$this->user->hasActiveSubscription()){
            $company = \App\Models\Company::where('is_default', 1)->first();
            Config::set('site.currency_sign', \App\Helpers\Helper::getCurrencySymbol($company->paypal_currency_code));
            $data['title'] = 'Subscription plan';
            $data['packages'] = \App\Models\Package::get();
            return view('vendor.custom.common.subscription.packages', $data);
        }else{
            \Alert::error(__('admin.opps'))->flash();
            return redirect(url('admin/dashboard'));
        }
    }

    /**
     * Subscribe user in a plan.
     * @return \Illuminate\Http\Response
     */
    public function subscribeSubscription(Request $request, \App\Models\Package $package){
        try {
            // get access token
            $accessToken = $this->getAccessToken();
            return $this->curlSubscription($package, $accessToken);
        } catch (\Exception $ex) {
            \Alert::error($ex->getMessage())->flash();
        }
        return redirect(url('admin/subscription/packages'));
    }

    public function getAccessToken() {
        $credential = $this->apiContext->getCredential();

        $ch = curl_init();
        $clientId = $credential->getClientId();
        $secret = $credential->getClientSecret();

        curl_setopt($ch, CURLOPT_URL, "https://api.paypal.com/v1/oauth2/token");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $clientId.":".$secret);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

        $result = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($result);
        return $json->access_token;
    }

    public function curlSubscription($package, $accessToken) {
        $startDate = '';

        switch ($package->billing_interval) {
            case 'Day':
                $startDate = \Carbon\Carbon::now()->addDay()->format('Y-m-d\TH:i:s\Z');
                break;
            case 'Week':
                $startDate = \Carbon\Carbon::now()->addWeek()->format('Y-m-d\TH:i:s\Z');
                break;
            case 'Month':
                $startDate = \Carbon\Carbon::now()->addMonth()->format('Y-m-d\TH:i:s\Z');
                break;
            case 'Year':
                $startDate = \Carbon\Carbon::now()->addYear()->format('Y-m-d\TH:i:s\Z');
                break;
            default:
                $startDate = \Carbon\Carbon::now()->addMinutes(5)->format('Y-m-d\TH:i:s\Z');
                break;
        }

        $url = "https://api.paypal.com/v1/billing/subscriptions";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);;
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            'Accept: application/json',
            'Authorization: '."Bearer ". $accessToken,
            'PayPal-Request-Id: '."SUBSCRIPTION-".$startDate,
            'Prefer: return=representation',
            'Content-Type: application/json',
        );

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = array(
            'plan_id' => $package->pay_plan_id,
            'start_time' => $startDate,
            'subscriber' => array(
                'name' => array(
                    'given_name' => $this->user->last_name,
                    'surname' => $this->user->first_name
                ),
                'email_address' => $this->user->email
            ),
            'application_context' => array(
                'brand_name' => 'Tuning Service Subscription',
                'locale' => 'en-UK',
                'shipping_preference' => 'SET_PROVIDED_ADDRESS',
                'user_action' => 'SUBSCRIBE_NOW',
                'payment_method' => array(
                    'payer_selected' => 'PAYPAL',
                    'payee_preferred' => 'IMMEDIATE_PAYMENT_REQUIRED',
                ),
                'return_url' => route('paypal.subscription.execute').'?success=true',
                'cancel_url' => route('paypal.subscription.execute').'?success=false'
            )
        );

        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);

        $respObj = json_decode($resp);
        // dd($respObj);
        return redirect()->away($respObj->links[0]->href);
    }

    /**
     * Execute subscription status.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function executeSubscription(Request $request){
        if ($request->has('success') && $request->query('success') == 'true') {
            $id = $request->subscription_id;
            $url = "https://api.paypal.com/v1/billing/subscriptions/{$id}";

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPGET, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $headers = array(
                'Accept: application/json',
                'Authorization: '."Bearer ". $this->getAccessToken(),
                'Prefer: return=representation',
                'Content-Type: application/json',
            );
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

            $resp = curl_exec($curl);
            curl_close($curl);

            $subscriptionDetail = json_decode($resp);

            $company = \App\Models\Company::where('is_default', 1)->first();
            $currencySymbol = \App\Helpers\Helper::getCurrencySymbol($company->paypal_currency_code);

            try {
                // Execute agreement
                $subscription = new \App\Models\Subscription();
                $subscription->user_id = $this->user->id;
                $subscription->pay_agreement_id = $id;
                $subscription->description = 'Amount: '.$currencySymbol.round($subscriptionDetail->billing_info->last_payment->amount->value);
                $subscription->start_date = \Carbon\Carbon::parse($subscriptionDetail->start_time)->format('Y-m-d H:i:s');
                $subscription->status = $subscriptionDetail->status;
                $subscription->save();
                \Alert::success(__('admin.company_subscribed'))->flash();
            } catch (\Exception $ex) {
                \Alert::error($ex->getMessage())->flash();
            }
        }else {
            \Alert::error(__('admin.company_not_subscribed'))->flash();
        }
        return redirect(url('admin/dashboard'));
    }

    /**
     * Cancel subscription
     * @param \App\Models\Subscription $subscription
     * @return $response
     */
    public function cancelSubscription(\App\Models\Subscription $subscription){
        $user = \App\User::where("id",$subscription->user_id)->first();
        if($subscription->is_trial==1){
            $subscription->status = 'Cancelled';
            if($subscription->save()){
				\Alert::success(__('admin.company_cancelled_subscription'))->flash();
			}else{
				\Alert::error(__('admin.opps'))->flash();
			}
        }
        else {
            try {
                $url = "https://api.paypal.com/v1/billing/subscriptions/{$subscription->pay_agreement_id}/cancel";

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                $headers = array(
                    'Accept: application/json',
                    'Authorization: '."Bearer ". $this->getAccessToken(),
                    'Prefer: return=representation',
                    'Content-Type: application/json',
                );
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                $data = [
                    'reason' => 'Cancel the subscription'
                ];
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                $resp = curl_exec($curl);
                curl_close($curl);

                $subscription->status = 'Cancelled';
                if($subscription->save()){
                    \Alert::success(__('admin.company_cancelled_subscription'))->flash();
                }else{
                    \Alert::error(__('admin.opps'))->flash();
                }
            }catch(\Exception $e){
                \Alert::error($e->getMessage())->flash();
            }
        }
        return redirect(url('admin/subscription?company='.$user->company_id));
    }

    /**
     * update subscription status.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function immediateCancelSubscription(\App\Models\Subscription $subscription, Request $request){
        $user = \App\User::where("id",$subscription->user_id)->first();
        if($subscription->is_trial == 1){
            $subscription->status = 'Suspended';
            if($subscription->save()){
				\Alert::success(__('admin.company_cancelled_subscription'))->flash();
			}else{
				\Alert::error(__('admin.opps'))->flash();
			}
        }
        else{
            try {
                $url = "https://api.paypal.com/v1/billing/subscriptions/{$subscription->pay_agreement_id}/suspend";

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                $headers = array(
                    'Accept: application/json',
                    'Authorization: '."Bearer ". $this->getAccessToken(),
                    'Prefer: return=representation',
                    'Content-Type: application/json',
                );
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                $data = [
                    'reason' => 'Cancel the subscription immediately'
                ];
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                $resp = curl_exec($curl);
                curl_close($curl);
                \Alert::success(__('admin.company_cancelled_subscription'))->flash();
            }catch(\Exception $e){
                \Alert::error($e->getMessage())->flash();
            }
        }
        $company = $subscription->owner;
        return redirect(url('admin/subscription?company='.$user->company_id));
    }

}
