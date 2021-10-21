<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Requests\CompanyRegisterFront;
use App\Http\Controllers\Controller;
use App\Mail\RegisterCompanyFront;
use App\Mail\NewCompanyApply;

use App\Models\Company;
use App\Models\TuningCreditGroup;
use App\Models\Package;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use PayPal\Api\ItemList;
use PayPal\Api\Payment;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\Payer;

use PayPal\Api\AgreementStateDescriptor;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\Agreement;
use PayPal\Api\Plan;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use Config;

class CompanyController extends Controller
{
	
	public function companies(Request $request){
		$qry = $request->all();
		if(!empty($qry) && isset($qry['keyword']) && isset($qry['sort'])  ){
			$keyword = $qry['keyword'];
			$sort = $qry['sort'];
		}
		//$company = Company::all();
		//with('tuningCreditGroups')
		$company = Company::where('is_public', '1')->with('tuningCreditGroups', 'tuningCreditGroups.tuningCreditTires')->get()->toArray();
		//dd($company);
		return view('Frontend.companies',compact('company'));
	}
	
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$company = Company::all();
		return view('Frontend.create',compact('company'));
    }
	
	/**
     * Store a details of payment with paypal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	
	 public function postPaymentWithpaypal(CompanyRegisterFront $request){
		 $company = new \App\Models\Company();
		 $company->name = $request->name;
		 $company->main_email_address = $request->main_email_address;
         
         if ($request->has('own_domain')) {
            $company->domain_link = $request->own_domain;
         } else {
            $company->domain_link = 'https://'.$request->domain_link;
         }
		 $company->address_line_1 = $request->address_line_1;
		 $company->address_line_2 = $request->address_line_2;
		 $company->town = $request->town;
		 $company->country = $request->country;
		 $company->vat_number = $request->vat_number;
		
		 if($company->save()){
		  	 $companyUser = new \App\User();
			 $companyUser->company_id = $company->id;
			 $companyUser->tuning_credit_group_id = Null;
			 $companyUser->first_name =  $request->name;
			 $companyUser->last_name = $request->name;
			 $companyUser->lang = 'en';
			 $companyUser->email = $request->main_email_address;
			 $companyUser->password =  Hash::make($request->password);
			 $companyUser->business_name =  $request->name;
			 $companyUser->address_line_1 =  $request->address_line_1;
			 $companyUser->address_line_2 =  $request->address_line_2;
			 $companyUser->county =  $request->country;
			 $companyUser->town =  $request->town;
			 $companyUser->is_master = 0;
			 $companyUser->is_admin = 1;
			 $companyUser->is_active = 0;
			 $companyUser->save();
			
			 $emailTemplates = \App\Models\EmailTemplate::where('company_id', 1)->whereIn('label', ['customer-welcome-email', 'new-file-service-created-email', 'file-service-modified-email', 'file-service-processed-email','new-ticket-created','new-file-ticket-created','reply-to-your-ticket'])->get();
				 if($emailTemplates->count() > 0){
					 foreach($emailTemplates as $emailTemplate){
						 $userTemplate = $emailTemplate->replicate(); 
						 $userTemplate->company_id = $company->id;
						 $userTemplate->save();
					 }
				 }
				$mainCompany = Company::where('id', '1')->first()->toArray();
				Config::set('mail.driver', $mainCompany['mail_driver']);
                Config::set('mail.host', $mainCompany['mail_host']);
                Config::set('mail.port', $mainCompany['mail_port']);
				Config::set('mail.encryption', $mainCompany['mail_encryption']);
                Config::set('mail.username', $mainCompany['mail_username']);
                Config::set('mail.password', $mainCompany['mail_password']);
                Config::set('mail.from.address', $mainCompany['mail_username']);
                Config::set('mail.from.name', $mainCompany['name']);
                Config::set('app.name', $mainCompany['name']);
			 	$companyMail =  'test123@yopmail.com';
			 	try{
					\Mail::to($mainCompany['main_email_address'])->send(new NewCompanyApply($companyUser,$mainCompany));
				}catch(\Exception $e){
				}
			 	return redirect()->route('thankyou')->with('Regististration received, Please wait for your application to be processed');
		}else{
			return redirect()->back()->with('error', 'Unknown error occurred');
		}
		
	 }
	
	public function thankyou(){
		 $msg = 'Regististration received, Please wait for your application to be processed';
		return view('Frontend.thankyou',compact('msg'));
	}	
   public function postPaymentWithpaypal1(CompanyRegisterFront $request){
				
		/** setup PayPal api context **/		
		
        $paypalConf = \Config::get('paypal');		
		$paypalConf['client_id'] = 'AdCpLIlE528OLfuUBCpMG2ZyXO3Om5EmSKDnsjKZNBoj68r6ElMraX4PeV-ac8WtCvovQUZF_9RIja-x';
		$paypalConf['secret'] = 'EKXQRFQSdB3Mn8958gNR7YejYhW9mEUk6lXx0psc0rWJwQDvDcqxIMJGbUidJ6N8Ta4n1ZKIdDDkVAxf';
        $this->apiContext = new ApiContext(new OAuthTokenCredential($paypalConf['client_id'], $paypalConf['secret']));
		$paypalConf['settings']['mode'] = 'sandbox';
        $this->apiContext->setConfig($paypalConf['settings']);
		
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item = new Item();
        $item->setName($request->name) /** item name **/
            ->setCurrency('GBP')
            ->setQuantity(1)
            ->setPrice($request->amount); /** unit price **/

        $item_list = new ItemList();
        $item_list->setItems([$item]);

        $details = new Details(); 
        $details->setShipping(0.00);
                //->setTax($request->item_tax)
                //->setSubtotal($request->item_amount);

        $amount = new Amount();
        $amount->setCurrency('GBP')
            ->setTotal($request->amount)
            ->setDetails($details);
			

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Subscription package')
            ->setInvoiceNumber(rand(88888888, 99999999));

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(route('paypal.payment.status.main'))
            ->setCancelUrl(route('paypal.payment.status.main'));

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
			
		
		
        try{ 
            $payment->create($this->apiContext);
			
			$request->domain_link = $request->domain_prefix."".$request->domain_suffix;
			
			/** add payment ID to session **/
        \Session::put([
            'paypal_payment_id'=> $payment->getId(),
            'name'=> $request->name,
            'main_email_address'=> $request->main_email_address,
            'password'=> $request->password,
            'domain_link'=> $request->domain_link,
            'address_line_1'=> $request->address_line_1,
            'address_line_2'=> $request->address_line_2,
            'town'=> $request->town,
            'country'=> $request->country,
            'vat_number'=> $request->vat_number,
            'package_id'=> $request->package_id,
            'amount'=> $request->amount,
        ]);
		
		$package_id = \Session::get('package_id');
			
        }catch(PayPal\Exception\PayPalConnectionException $ex) {
			//dd($ex);
            \Alert::error($ex->getMessage())->flash();
            return redirect()->route('register-account.create',$package_id)->with('error',$ex->getMessage());
        }catch (\Exception $ex) {
			//dd($ex);
            \Alert::error($ex->getMessage())->flash();
            return redirect()->route('register-account.create',$package_id)->with('error',$ex->getMessage());
        }
        foreach($payment->getLinks() as $link) {
            if($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        

        if(isset($redirect_url)) {
            /** redirect to paypal **/
            return redirect()->away($redirect_url);
        }
        //\Alert::error('Unknown error occurred')->flash();
        return redirect()->route('register-account.create')->with('error','Unknown error occurred');
    }

    

    /**
     * handle payment status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPaymentStatus(Request $request){
		
        $payment_id = \Session::get('paypal_payment_id');
        $name = \Session::get('name');
        $main_email_address = \Session::get('main_email_address');
        $password = \Session::get('password');
        $domain_link = \Session::get('domain_link');
        $address_line_1 = \Session::get('address_line_1');
        $address_line_2 = \Session::get('address_line_2');
        $town = \Session::get('town');
        $country = \Session::get('country');
        $vat_number = \Session::get('vat_number');
        $package_id = \Session::get('package_id');
        $amount = \Session::get('amount');
		
		//dd(session()->all());
		
        \Session::forget('paypal_payment_id');
        \Session::forget('name');
        \Session::forget('main_email_address');
        \Session::forget('password');
        \Session::forget('domain_link');
        \Session::forget('address_line_1');
        \Session::forget('address_line_2');
        \Session::forget('town');
        \Session::forget('country');
        \Session::forget('vat_number');
        //\Session::forget('package_id');
        \Session::forget('amount');
		
        if (empty($request->PayerID) || empty($request->token)) {
            \Alert::error('Payment failed')->flash();
            return redirect()->route('register-account.create',$package_id)->with('error','Payment failed');
        }
        try{
			
			$company = \App\Models\Company::where('is_default', 1)->first();
			if($company){
				\Config::set('paypal.client_id', $company->paypal_client_id);
				\Config::set('paypal.secret', $company->paypal_secret);
			}
			/** setup PayPal api context **/
			$paypalConf = \Config::get('paypal');
			$this->apiContext = new ApiContext(new OAuthTokenCredential($paypalConf['client_id'], $paypalConf['secret']));
			$this->apiContext->setConfig($paypalConf['settings']);
			
            $payment = Payment::get($payment_id, $this->apiContext);			
            $execution = new PaymentExecution();
			
            $execution->setPayerId($request->PayerID);
            $result = $payment->execute($execution, $this->apiContext);
            $transactions = $result->getTransactions(); 
            $transaction = $transactions[0]; 
            $relatedResources = $transaction->getRelatedResources(); 
            $relatedResource = $relatedResources[0]; 
            $paypalOrder = $relatedResource->getOrder();
           
			if ($result->getState() == 'approved') {
				$company = new \App\Models\Company();
				$company->name = $name;
				$company->main_email_address = $main_email_address;				
				$company->domain_link = $domain_link;
				$company->address_line_1 = $address_line_1;
				$company->address_line_2 = $address_line_2;
				$company->town = $town;
				$company->country = $country;
				$company->vat_number = $vat_number;
				$company->save();		
					
				
				if($company->owner == NULL){
					/* register company*/
					if($company->name && $company->main_email_address && $company->address_line_1 && $company->town && $company->country && $company->domain_link){

						$companyUser = new \App\User();
						$companyUser->company_id = $company->id;
						$companyUser->tuning_credit_group_id = Null;
						$companyUser->first_name = $name;
						$companyUser->last_name = $name;
						$companyUser->lang = 'en';
						$companyUser->email = $main_email_address;
						$companyUser->password = Hash::make($password);
						$companyUser->business_name = $name;
						$companyUser->address_line_1 = $address_line_1;
						$companyUser->address_line_2 = $address_line_2;
						$companyUser->county = $country;
						$companyUser->town = $town;
						$companyUser->is_master = 0;
						$companyUser->is_admin = 1;
						
						if($companyUser->save()){
							$emailTemplates = \App\Models\EmailTemplate::where('company_id', 1)->where('subject', 'Welcome: Company has been registered')->get();
						
							if($emailTemplates->count() > 0){
								foreach($emailTemplates as $emailTemplate){
									$userTemplate = $emailTemplate->replicate(); 
									$userTemplate->company_id = $company->id;
									$userTemplate->save();
									//dd($userTemplate->save());
								}
							}
							try{
								\Mail::to($companyUser->email)->send(new RegisterCompanyFront($companyUser));
							}catch(\Exception $e){
														
							}
						}
					}
				}else{
					$companyOwner = $company->owner;
					$companyOwner->email = $main_email_address;
					$companyOwner->save();
				}
				
				
				/** add payment ID to session **/
				\Session::put([
					'companyUser'=> $companyUser->id,
				]);
				
			    return redirect()->route('paypal.subscribe.subscription',$package_id);
			    //return redirect()->route('register-account.create')->with('success','You have successfully registered you company. Please check your email to set the password');
                
            }
            
        }catch(\Exception $ex){
			return redirect()->route('register-account.create',$package_id)->with('error','Something went wrong. Please try again');
           
        }
    }
	
	
	
	/**
     * Subscribe user in a plan.
     * @return \Illuminate\Http\Response
     */
    public function subscribeSubscription(Request $request, \App\Models\Package $package){
		
		$company = \App\Models\Company::where('is_default', 1)->first();
			if($company){
				\Config::set('paypal.client_id', $company->paypal_client_id);
				\Config::set('paypal.secret', $company->paypal_secret);
			}
		
		/** setup PayPal api context **/		
		
        $paypalConf = \Config::get('paypal');
        $this->apiContext = new ApiContext(new OAuthTokenCredential($paypalConf['client_id'], $paypalConf['secret']));
        $this->apiContext->setConfig($paypalConf['settings']);
		
        
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
        $agreement = new Agreement();
		
		
        if($package->billing_interval)
        $agreement->setName($package->name)
            ->setDescription($package->name)
            ->setStartDate($startDate);
        /* Set agreement Plan */
        
        $plan = new Plan();
        $plan->setId($package->pay_plan_id);
        $agreement->setPlan($plan);
		
        /* Overwrite merchant prefeerences */
        $merchantPreferences = new MerchantPreferences();
        $merchantPreferences->setReturnUrl(route('paypal.execute.subscription').'?success=true')
            ->setCancelUrl(route('paypal.execute.subscription').'?success=false');

        $agreement->setOverrideMerchantPreferences($merchantPreferences);

        /* Add payer type */
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $agreement->setPayer($payer);
		
        try {
            /* Create agreement */ 
            $agreement = $agreement->create($this->apiContext);			
            $approvalUrl = $agreement->getApprovalLink();
			
            if($approvalUrl) {
                return redirect()->away($approvalUrl);
            }
			
        }catch (PayPal\Exception\PayPalConnectionException $ex) {
			
            \Alert::error($ex->getMessage())->flash();
			 return redirect()->route('register-account.create',$package_id)->with('error',$ex->getMessage());
        }catch (\Exception $ex) {
			
            \Alert::error($ex->getMessage())->flash();
			 return redirect()->route('register-account.create',$package_id)->with('error',$ex->getMessage());
        }
        //return redirect(url('admin/subscription/packages'));
    }

	
	
    /**
     * Execute subscription status.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function executeSubscription(Request $request){		
        if ($request->has('success') && $request->query('success') == 'true') {
			$company = \App\Models\Company::where('is_default', 1)->first();
			if($company){
				\Config::set('paypal.client_id', $company->paypal_client_id);
				\Config::set('paypal.secret', $company->paypal_secret);
			}
			
			/** setup PayPal api context **/		
			
			$paypalConf = \Config::get('paypal');	
			$this->apiContext = new ApiContext(new OAuthTokenCredential($paypalConf['client_id'], $paypalConf['secret']));
			$this->apiContext->setConfig($paypalConf['settings']);
		
			$companyUser = \Session::get('companyUser');
			$package_id = \Session::get('package_id');
			\Session::forget('companyUser');
			\Session::forget('package_id');
            $token = $request->query('token');
            $agreement = new \PayPal\Api\Agreement();
			
            try {
                // Execute agreement
                $agreement = $agreement->execute($token, $this->apiContext);                
                $subscription = new \App\Models\Subscription();
                $subscription->user_id = $companyUser;
                $subscription->pay_agreement_id = $agreement->id;
                $subscription->description = $agreement->description;
                $subscription->start_date = \Carbon\Carbon::parse($agreement->start_date)->format('Y-m-d H:i:s');
                $subscription->status = $agreement->state;
                $subscription->save();
                \Alert::success(__('admin.company_subscribed'))->flash();
				return redirect()->route('register-account.create',$package_id)->with('success','You have successfully registered your company.');
            } catch (PayPal\Exception\PayPalConnectionException $ex) {
                \Alert::error($ex->getMessage())->flash();
				return redirect()->route('register-account.create',$package_id)->with('error',$ex->getMessage());
            } catch (\Exception $ex) {
                \Alert::error($ex->getMessage())->flash();
				return redirect()->route('register-account.create',$package_id)->with('error',$ex->getMessage());
            }
        }else {
			$package_id = \Session::get('package_id');
			\Session::forget('package_id');
            \Alert::error(__('admin.company_not_subscribed'))->flash();
			return redirect()->route('register-account.create',$package_id)->with('error','There is some error. Company has not been subscribed.');
        }
		
        //return redirect()->route('register-account.create',$package_id)->with('success','You have successfully registered your company.');
    }
	
	
	
}
