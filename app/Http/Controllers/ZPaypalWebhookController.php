<?php

namespace App\Http\Controllers;

use App\Mail\BillingSubscriptionCancelled;
use App\Mail\BillingSubscriptionCreated;
use PayPal\Auth\OAuthTokenCredential;
use App\Mail\BillingPaymentCompleted;
use App\Mail\BillingPaymentPending;
use App\Mail\BillingPaymentDenied;
use App\Http\Controllers\Controller;
use App\Models\ZsubscriptionPayment;
use PayPal\Api\AgreementDetails;
use Illuminate\Http\Request;
use App\Models\Zsubscription;
use PayPal\Rest\ApiContext;
use PayPal\Api\Agreement;
use App\Models\Company;
use Mail;
use Log;

class ZPaypalWebhookController extends Controller{

    private $apiContext;
    private $master;

    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct(){
        $this->master = Company::where('is_default', 1)->first();
        if($this->master){
            \Config::set('mail.driver', $this->master->mail_driver);
            \Config::set('mail.host', $this->master->mail_host);
            \Config::set('mail.port', $this->master->mail_port);
			\Config::set('mail.encryption', $this->master->mail_encryption);
            \Config::set('mail.username', $this->master->mail_username);
            \Config::set('mail.password', $this->master->mail_password);
            \Config::set('mail.from.address', $this->master->main_email_address);
            \Config::set('mail.from.name', $this->master->name);
            \Config::set('app.name', $this->master->name);
            \Config::set('backpack.base.project_name', $this->master->name);

            \Config::set('paypal.client_id', $this->master->paypal_client_id);
            \Config::set('paypal.secret', $this->master->paypal_secret);
            \Config::set('paypal.settings.mode', $this->master->paypal_mode);

        }
        $paypalConf = \Config::get('paypal');
		/*$paypalConf['client_id'] = 'AdCpLIlE528OLfuUBCpMG2ZyXO3Om5EmSKDnsjKZNBoj68r6ElMraX4PeV-ac8WtCvovQUZF_9RIja-x';
		$paypalConf['secret'] = 'EKXQRFQSdB3Mn8958gNR7YejYhW9mEUk6lXx0psc0rWJwQDvDcqxIMJGbUidJ6N8Ta4n1ZKIdDDkVAxf';
		$paypalConf['settings']['mode'] = 'sandbox';*/
        $this->apiContext = new ApiContext(new OAuthTokenCredential($paypalConf['client_id'], $paypalConf['secret']));
        $this->apiContext->setConfig($paypalConf['settings']);
    }

    /**
     * Handle paypal webhook events.
     * @return void
     */
    public function index(Request $request){
        Log::info($request->event_type);
        /* Check event type */

        switch ($request->event_type) {

            /* Subscription created */
            case 'BILLING.SUBSCRIPTION.CREATED':
                $resource = $request->resource;
                $subscription = Zsubscription::where('pay_agreement_id', $resource['id'])->first();
                if($subscription){

                    // Mail::to($this->master->owner->email)->send(new BillingSubscriptionCreated($subscription));
                    // Log::info('BILLING.SUBSCRIPTION.CREATED:: Subscription created.');

                }else{

                    Log::info('BILLING.SUBSCRIPTION.CREATED:: Agreement doesn\'t exists.');
                }
                break;
            /* Subscription cancelled */
            case 'BILLING.SUBSCRIPTION.CANCELLED':
                $resource = $request->resource;
                \Log::info(print_r($resource, true));
                $subscription = Zsubscription::where('pay_agreement_id', $resource['id'])->first();
                if($subscription){
                    $subscription->status = $resource['status'];
                    $subscription->save();

                    // Mail::to($this->master->owner->email)->send(new BillingSubscriptionCancelled($subscription));

                    // Log::info('BILLING.SUBSCRIPTION.CANCELLED:: Subscription cancelled.');

                }else{

                    Log::info('BILLING.SUBSCRIPTION.CANCELLED:: Agreement doesn\'t exists.');
                }
                break;

            /* Subscription suspended */
            case 'BILLING.SUBSCRIPTION.SUSPENDED':
                $resource = $request->resource;
                $subscription = Zsubscription::where('pay_agreement_id', $resource['id'])->first();
                if($subscription){
                    $subscription->status = $resource['status'];
                    $subscription->save();

                    Log::info('BILLING.SUBSCRIPTION.SUSPENDED:: Subscription suspended.');

                }else{

                    Log::info('BILLING.SUBSCRIPTION.SUSPENDED:: Agreement doesn\'t exists.');
                }
                break;

            /* Subscription suspended */
            case 'BILLING.SUBSCRIPTION.RE-ACTIVATED':
                $resource = $request->resource;
                $subscription = Zsubscription::where('pay_agreement_id', $resource['id'])->first();
                if($subscription){
                    $subscription->status = $resource['status'];
                    $subscription->save();

                    Log::info('BILLING.SUBSCRIPTION.RE-ACTIVATED:: Subscription re-activated.');

                }else{

                    Log::info('BILLING.SUBSCRIPTION.RE-ACTIVATED:: Agreement doesn\'t exists.');
                }
                break;

            /* Subscription Payment completed */
            case 'PAYMENT.SALE.COMPLETED':
            	$resource = $request->resource;
				// \Log::info(print_r($resource, true));
                $subscription = Zsubscription::where('pay_agreement_id', $resource['billing_agreement_id'])->first();

                if($subscription){
                    // $agreement = \PayPal\Api\Agreement::get($subscription->pay_agreement_id, $this->apiContext);
                    // $agreementDetails = $agreement->getAgreementDetails();
                    $billingInfo = $this->getSubscriptionBillingInfo($subscription->pay_agreement_id);

                    $subscriptionPayment = ZsubscriptionPayment::where('pay_txn_id', $resource['id'])->first();
                    if(!$subscriptionPayment){
                    	$subscriptionPayment = new ZsubscriptionPayment();
                    }
                    //\Log::info(print_r($agreementDetails, true));
					//\Log::info(print_r($agreementDetails->getLastPaymentAmount(), true));
                    $subscriptionPayment->zsubscription_id = $subscription->id;
                    $subscriptionPayment->pay_txn_id = $resource['id'];
                    $subscriptionPayment->next_billing_date = \Carbon\Carbon::parse($billingInfo->next_billing_time)->format('Y-m-d H:i:s');
                    $subscriptionPayment->last_payment_date  = \Carbon\Carbon::parse($billingInfo->last_payment->time)->format('Y-m-d H:i:s');
                    //$subscriptionPayment->last_payment_amount  = $agreementDetails->getLastPaymentAmount()->value;
					if(isset($billingInfo->last_payment->amount->value)) {
						$subscriptionPayment->last_payment_amount  = $billingInfo->last_payment->amount->value;
                    }

					$subscriptionPayment->failed_payment_count  = $billingInfo->failed_payments_count;
                    $subscriptionPayment->status = $resource['state'];

                    if($subscriptionPayment->save()){
                        // Mail::to($this->master->owner->email)->send(new BillingPaymentCompleted($subscription));
                    }
                    Log::info('PAYMENT.SALE.COMPLETED:: Payment sale completed.');

                }else{

                    Log::info('PAYMENT.SALE.COMPLETED:: Agreement doesn\'t exists.');
                }
                break;

            /* Subscription Payment Denied */
            case 'PAYMENT.SALE.DENIED':
            	$resource = $request->resource;
                $subscription = Zsubscription::where('pay_agreement_id', @$resource['billing_agreement_id'])->first();

                if($subscription){
                	// $agreement = \PayPal\Api\Agreement::get($subscription->pay_agreement_id, $this->apiContext);
                    // $agreementDetails = $agreement->getAgreementDetails();
                    $billingInfo = $this->getSubscriptionBillingInfo($subscription->pay_agreement_id);

                    $subscriptionPayment = ZsubscriptionPayment::where('pay_txn_id', $resource['id'])->first();
                    if(!$subscriptionPayment){
                    	$subscriptionPayment = new ZsubscriptionPayment();
                    }

                    $subscriptionPayment->subscription_id = $subscription->id;
                    $subscriptionPayment->pay_txn_id = $resource['id'];
                    $subscriptionPayment->next_billing_date = \Carbon\Carbon::parse($billingInfo->next_billing_time)->format('Y-m-d H:i:s');
                    $subscriptionPayment->last_payment_date  = \Carbon\Carbon::parse($billingInfo->last_payment->time)->format('Y-m-d H:i:s');
                    $subscriptionPayment->last_payment_amount  = $billingInfo->last_payment->amount->value;
                    $subscriptionPayment->failed_payment_count  = $billingInfo->failed_payments_count;
                	$subscriptionPayment->status = $resource['state'];

                	if($subscriptionPayment->save()){
                		// Mail::to($this->master->owner->email)->send(new BillingPaymentPending($subscription));
                	}

                    Log::info('PAYMENT.SALE.Denied:: Payment sale denied.');

                }else{

                    Log::info('PAYMENT.SALE.Denied:: Agreement doesn\'t exists.');
                }
            	break;

            /* Subscription payment pending */
            case 'PAYMENT.SALE.PENDING':
            	$resource = $request->resource;
                $subscription = Zsubscription::where('pay_agreement_id', @$resource['billing_agreement_id'])->first();

                if($subscription){
                	// $agreement = \PayPal\Api\Agreement::get($subscription->pay_agreement_id, $this->apiContext);
                    // $agreementDetails = $agreement->getAgreementDetails();
                    $billingInfo = $this->getSubscriptionBillingInfo($subscription->pay_agreement_id);

                    $subscriptionPayment = ZsubscriptionPayment::where('pay_txn_id', $resource['id'])->first();
                    if(!$subscriptionPayment){
                    	$subscriptionPayment = new ZsubscriptionPayment();
                    }

                    $subscriptionPayment->subscription_id = $subscription->id;
                    $subscriptionPayment->pay_txn_id = $resource['id'];
                    $subscriptionPayment->next_billing_date = \Carbon\Carbon::parse($billingInfo->next_billing_time)->format('Y-m-d H:i:s');
                    $subscriptionPayment->last_payment_date  = \Carbon\Carbon::parse($billingInfo->last_payment->time)->format('Y-m-d H:i:s');
                    $subscriptionPayment->last_payment_amount  = $billingInfo->last_payment->amount->value;
                    $subscriptionPayment->failed_payment_count  = $billingInfo->failed_payments_count;
                	$subscriptionPayment->status = $resource['state'];

                	if($subscriptionPayment->save()){
                		// Mail::to($this->master->owner->email)->send(new BillingPaymentPending($subscription));
                	}
                    Log::info('PAYMENT.SALE.PENDING::Payment sale pending.');

                }else{

                    Log::info('PAYMENT.SALE.PENDING:: Agreement doesn\'t exists.');
                }
            	break;
            default:
            break;
        }


    }

    public function getAccessToken() {
        $ch = curl_init();
        $clientId = "AdibmcjffSYZR9TSS5DuKIQpnf80KfY-3pBGd30JKz2Ar1xHIipwijo4eZOJvbDCFpfmOBItDqZoiHmM";
        $secret = "EOsuz6CpyktWG37wbVCvS79J6LQ4Eo0s7kVPxSbfX_DU6bBn7wfuPv71APVTQwHNS9NTn8w3b24uI8q_";

        curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/oauth2/token");
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

    public function getSubscriptionBillingInfo($id) {
        $url = "https://api.sandbox.paypal.com/v1/billing/subscriptions/{$id}";

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

        $subscriptionDetails = json_decode($resp);
        $billingInfo = $subscriptionDetails->billing_info;
        return $billingInfo;
    }
}
