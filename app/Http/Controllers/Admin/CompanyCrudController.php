<?php

namespace App\Http\Controllers\Admin;
//changes
use App\Models\Company;

use App\Http\Controllers\MasterController;
use App\Http\Requests\CompanyRequest as StoreRequest;
use App\Http\Requests\CompanyRequest as UpdateRequest;
use App\Mail\WelcomeCustomer;
use App\Mail\CompanyActivateEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use View, Config;

/**
 * Class CompanyCrudController
 * @param App\Http\Controllers\MasterController
 * @return CrudPanel $crud
 */
class CompanyCrudController extends MasterController
{
    /**
     * Class Setup
     */
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Company');
        $this->crud->setRoute('admin/company');
        $this->crud->setEntityNameStrings('company', 'companies');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addButtonFromView('line', 'subscription', 'subscription' , 'end');
        $user = \Auth::guard('admin')->user();
        $this->crud->addButtonFromView('line', 'resend_company_password_reset_link', 'resend_company_password_reset_link' , 'end');
        $this->crud->addButtonFromView('line', 'switch_account_as_company', 'switch_account_as_company' , 'end');
        $this->crud->addButtonFromView('line', 'free_customer_subscribe', 'free_customer_subscribe' , 'end');
        //changes
			$this->crud->addButtonFromView('line', 'is_public', 'is_public' , 'end');
			$this->crud->addButtonFromView('line', 'is_active', 'is_active' , 'end');
        $user = \Auth::guard('admin')->user();
        $this->crud->query->where('id', '!=', $user->company->id);
        $this->crud->query->orderBy('id', 'DESC');

        /*
        |--------------------------------------------------------------------------
        | Basic Crud column Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
            'name' => 'name',
            'label' => 'Name',
        ]);

        $this->crud->addColumn([
            'name' => 'domain_link',
            'label' => 'Domain Link',
        ]);

        $this->crud->addColumn([
            'name' => 'total_customers',
            'label' => 'Total Customers',
        ]);

        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => 'Created At',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Basic Crud Field Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addField([
            'name' => 'name',
            'label' => "Name",
            'type' => 'text',
            'tab' => 'Name and address',
            'attributes'=>['placeholder'=>'Name'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank',
            'type' => 'blank',
            'tab' => 'Name and address'
        ]);

        $this->crud->addField([
            'name' => 'address_line_1',
            'label' => "Address line 1",
            'type' => 'text',
            'tab' => 'Name and address',
            'attributes'=>['placeholder'=>'Address line 1'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank1',
            'type' => 'blank',
            'tab' => 'Name and address'
        ]);

        $this->crud->addField([
            'name' => 'address_line_2',
            'label' => "Address line 2 <small class='text-muted'>(optional)</small>",
            'type' => 'text',
            'tab' => 'Name and address',
            'attributes'=>['placeholder'=>'Address line 2'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank2',
            'type' => 'blank',
            'tab' => 'Name and address'
        ]);

        $this->crud->addField([
            'name' => 'town',
            'label' => "Town",
            'type' => 'text',
            'tab' => 'Name and address',
            'attributes'=>['placeholder'=>'Town'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank3',
            'type' => 'blank',
            'tab' => 'Name and address'
        ]);

        $this->crud->addField([
            'name' => 'post_code',
            'label' => "Post Code <small class='text-muted'>(optional)</small>",
            'type' => 'text',
            'tab' => 'Name and address',
            'attributes'=>['placeholder'=>'Post Code'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank4',
            'type' => 'blank',
            'tab' => 'Name and address'
        ]);

        $this->crud->addField([
            'name' => 'country',
            'label' => "Country",
            'type' => 'text',
            'tab' => 'Name and address',
            'attributes'=>['placeholder'=>'Country'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank5',
            'type' => 'blank',
            'tab' => 'Name and address'
        ]);

        $this->crud->addField([
            'name' => 'state',
            'label' => "State/Province <small class='text-muted'>(optional)</small>",
            'type' => 'text',
            'tab' => 'Name and address',
            'attributes'=>['placeholder'=>'State/Province'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank6',
            'type' => 'blank',
            'tab' => 'Name and address'
        ]);

        $this->crud->addField([
            'name' => 'file',
            'label' => "Logo <small class='text-muted'>(optional)</small>",
            'type' => 'preview_file',
            'tab' => 'Name and address',
            'value'=> 'default-logo.png',
            'upload' => true,
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank7',
            'type' => 'blank',
            'tab' => 'Name and address'
        ]);

		//changes
			/*$this->crud->addField([
				'name' => 'rating',
				'label' => "Rating",
				'type' => 'text',
				'tab' => 'Name and address',
				'attributes'=>['placeholder'=>'Rating'],
				'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
			]);
			 $this->crud->addField([
				'name'=> 'blank8',
				'type' => 'blank',
				'tab' => 'Name and address'
			]);*/

        $this->crud->addField([
            'name' => 'theme_color',
            'label' => "Theme color <small class='text-muted'>(optional)</small>",
            'type' => 'color_picker',
            'tab' => 'Name and address',
            'attributes'=>['placeholder'=>'Theme color'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank9',
            'type' => 'blank',
            'tab' => 'Name and address'
        ]);

        $this->crud->addField([
            'name' => 'copy_right_text',
            'label' => "Copy right text <small class='text-muted'>(optional)</small>",
            'type' => 'text',
            'tab' => 'Name and address',
            'attributes'=>['placeholder'=>'Copy right text'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);

        $this->crud->addField([
            'name' => 'domain_link',
            'label' => "Domain link",
            'type' => 'text',
            'tab' => 'Domain information',
            'attributes'=>['placeholder'=>'Domain link'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);


        $this->crud->addField([
            'name' => 'main_email_address',
            'label' => "Main email address",
            'type' => 'text',
            'tab' => 'Email address',
            'attributes'=>['placeholder'=>'Main email address'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank10',
            'type' => 'blank',
            'tab' => 'Email address'
        ]);

        $this->crud->addField([
            'name' => 'support_email_address',
            'label' => "Support email address <small class='text-muted'>(optional)</small>",
            'type' => 'text',
            'tab' => 'Email address',
            'attributes'=>['placeholder'=>'Support email address'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank11',
            'type' => 'blank',
            'tab' => 'Email address'
        ]);

        $this->crud->addField([
            'name' => 'billing_email_address',
            'label' => "Billing email address <small class='text-muted'>(optional)</small>",
            'type' => 'text',
            'tab' => 'Email address',
            'attributes'=>['placeholder'=>'Billing email address'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);

        $this->crud->addField([
            'name' => 'bank_account',
            'label' => "Bank account <small class='text-muted'>(optional)</small>",
            'type' => 'text',
            'tab' => 'Financial information',
            'attributes'=>['placeholder'=>'Bank account'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank12',
            'type' => 'blank',
            'tab' => 'Financial information'
        ]);

        $this->crud->addField([
            'name' => 'bank_identification_code',
            'label' => "Bank identification code (BIC) <small class='text-muted'>(optional)</small>",
            'type' => 'text',
            'tab' => 'Financial information',
            'attributes'=>['placeholder'=>'Bank identification code (BIC)'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank13',
            'type' => 'blank',
            'tab' => 'Financial information'
        ]);

        $this->crud->addField([
            'name' => 'vat_number',
            'label' => "VAT Number <small class='text-muted'>(optional)</small>",
            'type' => 'text',
            'tab' => 'Financial information',
            'attributes'=>['placeholder'=>'VAT Number'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank14',
            'type' => 'blank',
            'tab' => 'Financial information'
        ]);

        $this->crud->addField([
            'name' => 'vat_percentage',
            'label' => "VAT%",
            'type' => 'number',
            'tab' => 'Financial information',
            'attributes'=>['placeholder'=>'VAT%'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);

        $this->crud->addField([
            'name' => 'customer_note',
            'label' => "Notes to customer <small class='text-muted'>(optional)</small>",
            'type' => 'textarea',
            'tab' => 'Notes to customers',
            'attributes'=>['placeholder'=>'Notes to customers'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);

		/*$this->crud->addField([
            'name' => 'more_info',
            'label' => "More Info <small class='text-muted'>(Max 255 characters)</small>",
            'type' => 'textarea',
            'tab' => 'Notes to customers',
            'attributes'=>['placeholder'=>'More Info','maxlength'=>'255'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);*/

        $this->crud->addField([
            'name' => 'mail_driver',
            'label' => "Mail driver <small class='text-muted'>(optional)</small>",
            'type' => 'text',
            'value' => 'smtp',
            'tab' => 'SMTP information',
            'attributes'=>['placeholder'=>'Mail driver', 'readonly'=>'readonly'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ], 'create');

        $this->crud->addField([
            'name'=> 'blank15',
            'type' => 'blank',
            'tab' => 'SMTP information'
        ], 'create');

        $this->crud->addField([
            'name' => 'mail_host',
            'label' => "Mail host <small class='text-muted'>(optional)</small>",
            'type' => 'text',
            'tab' => 'SMTP information',
            'attributes'=>['placeholder'=>'Mail host'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ], 'create');

        $this->crud->addField([
            'name'=> 'blank16',
            'type' => 'blank',
            'tab' => 'SMTP information'
        ], 'create');

        $this->crud->addField([
            'name' => 'mail_port',
            'label' => "Mail port <small class='text-muted'>(optional)</small>",
            'type' => 'text',
            'tab' => 'SMTP information',
            'attributes'=>['placeholder'=>'Mail port'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ], 'create');

        $this->crud->addField([
            'name'=> 'blank17',
            'type' => 'blank',
            'tab' => 'SMTP information'
        ], 'create');

        $this->crud->addField([
            'name' => 'mail_username',
            'label' => "Mail username <small class='text-muted'>(optional)</small>",
            'type' => 'text',
            'tab' => 'SMTP information',
            'attributes'=>['placeholder'=>'Mail username'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ], 'create');

        $this->crud->addField([
            'name'=> 'blank18',
            'type' => 'blank',
            'tab' => 'SMTP information'
        ], 'create');

        $this->crud->addField([
            'name' => 'mail_password',
            'label' => "Mail password <small class='text-muted'>(optional)</small>",
            'type' => 'password',
            'tab' => 'SMTP information',
            'attributes'=>['placeholder'=>'Mail password'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ], 'create');


        $this->crud->addField([
            'name'=> 'blank19',
            'type' => 'blank',
            'tab' => 'Paypal information'
        ], 'create');

        $this->crud->addField([
            'name' => 'paypal_client_id',
            'label' => "Paypal client id <small class='text-muted'>(optional)</small>",
            'type' => 'text',
            'tab' => 'Paypal information',
            'attributes'=>['placeholder'=>'Paypal client id'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ], 'create');

        $this->crud->addField([
            'name'=> 'blank20',
            'type' => 'blank',
            'tab' => 'Paypal information'
        ], 'create');

        $this->crud->addField([
            'name' => 'paypal_secret',
            'label' => "Paypal secret id <small class='text-muted'>(optional)</small>",
            'type' => 'text',
            'tab' => 'Paypal information',
            'attributes'=>['placeholder'=>'Paypal secret'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ], 'create');

        $this->crud->addField([
            'name'=> 'blank21',
            'type' => 'blank',
            'tab' => 'Paypal information'
        ], 'create');

        $this->crud->addField([
            'name' => 'paypal_currency_code',
            'label' => "Paypal currency code <small class='text-muted'>(optional)</small>",
            'type' => 'text',
            'tab' => 'Paypal information',
            'attributes'=>['placeholder'=>'Paypal currency code'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ], 'create');

        $this->crud->addField([
            'name'=> 'blank22',
            'type' => 'blank',
            'tab' => 'Stripe information'
        ], 'create');

        $this->crud->addField([
            'name' => 'stripe_key',
            'label' => "Stripe Key <small class='text-muted'>(optional)</small>",
            'type' => 'text',
            'tab' => 'Stripe information',
            'attributes'=>['placeholder'=>'Stripe Key'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ], 'create');

        $this->crud->addField([
            'name'=> 'blank23',
            'type' => 'blank',
            'tab' => 'Stripe information'
        ], 'create');

        $this->crud->addField([
            'name' => 'stripe_secret',
            'label' => "Stripe Secret Key <small class='text-muted'>(optional)</small>",
            'type' => 'text',
            'tab' => 'Stripe information',
            'attributes'=>['placeholder'=>'Stripe Secret Key'],
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ], 'create');


        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }


	//changes
	public function companyAccountType($id){
		$company = Company::where('id',$id)->first();
		if($company->is_public ==1){
			$company->is_public = 0;
		}else{
			$company->is_public = 1;
		}
		$company->Save();
		$return ='company';
		if($id ==1){
			$return ='dashboard';
		}
		return redirect(backpack_url($return));
	}

	public function accountActivate( $companyId){
		$company = \App\Models\Company::find($companyId);
		$companyUser = $company->owner;
		//$user = User::where(['is_admin'=>1,'company_id'=>$companyId])->first();
		if($companyUser->is_active ==1){
			$companyUser->is_active = 0;
			$companyUser->Save();
			\Alert::success('Comapny has been deactivated successfully.')->flash();
		}else{
			$companyUser->is_active = 1;
			$companyUser->Save();
			$token = app('auth.password.broker')->createToken($companyUser);

			/*Config::set('mail.driver', $this->company->mail_driver);
			Config::set('mail.host', 'mail.24livehost.com');
			Config::set('mail.port', 25);
			Config::set('mail.encryption', $this->company->mail_encryption);
			Config::set('mail.username', 'wwwsmtp@24livehost.com');
			Config::set('mail.password', 'dsmtp909#');
			Config::set('mail.from.address','no-reply@advancedtuning.com' );
			Config::set('mail.from.name', 'advancedtuning');
			Config::set('app.name', $this->company->name);
			Config::set('backpack.base.project_name', $this->company->name);


			Config::set('mail.driver', 'smtp');
			Config::set('mail.host', 'localhost');
			Config::set('mail.port', 25);
			Config::set('mail.encryption', '');
			Config::set('mail.username', 'noreply@myremaps.com');
			Config::set('mail.password', '#0bWui37');
			Config::set('mail.from.address','noreply@myremaps.com' );
			Config::set('mail.from.name', 'noreply');
			Config::set('app.name', $this->company->name);
			Config::set('backpack.base.project_name', $this->company->name);

*/
			\Alert::success('Comapny has been activated successfully.')->flash();
			try{
            	\Mail::to($companyUser->email)->send( (new CompanyActivateEmail($companyUser, $token)));
			}catch(\Exception $e){
				\Alert::error('Error in SMTP: '.__('admin.opps'))->flash();
			}

		}
		return redirect(backpack_url('company'));
	}

     /**
     * Store resource
     * @param App\Http\Request\StoreRequest $request
     * @return $response
     */
    public function store(Request $request){
        $requestData = \Illuminate\Http\Request::capture();
        switch($request->current_tab){
            case 'nameandaddress':
                $validator = Validator::make($request->only(['name', 'country', 'state', 'town', 'address_line_1', 'address_line_2', 'post_code', 'logo', 'theme_color', 'copy_right_text']),[
                    'name' => 'bail|required|string|max:100',
                    'address_line_1'=> 'bail|required|string|max:100',
                    'address_line_2'=> 'bail|nullable|string|max:100',
                    'town'=> 'bail|required|string|max:50',
                    'post_code'=> 'bail|nullable|string|max:30',
                    'country'=> 'bail|required|string|max:50',
                    'state'=> 'bail|nullable|string|max:50',
					//'rating'=> 'bail|nullable|string|max:50',
                    'file'=> 'bail|nullable|image|mimes:jpg,png,jpeg',
                    'copy_right_text'=> 'bail|nullable|string|max:100'
                ]);

                $requestData->replace($request->only(['name', 'country', 'state', 'town', 'address_line_1', 'address_line_2', 'post_code', 'logo', 'theme_color', 'copy_right_text']));
                break;
            case 'domaininformation':
                $validator = Validator::make($request->only('domain_link'), [
                    'domain_link'=> 'bail|required|url|unique:companies,domain_link|max:100'
                ]);
                $requestData->replace($request->only('domain_link'));
                break;
            case 'emailaddress':
                // 'main_email_address'=> 'bail|required|email|unique:companies,main_email_address,'.$company->id.'|unique:users,email,'.$company->owner->id.'|max:100',
                $validator = Validator::make($request->only(['main_email_address', 'support_email_address', 'billing_email_address']), [
                    'main_email_address'=> 'bail|required|email|unique:companies,main_email_address|max:100',
                    'support_email_address'=> 'bail|nullable|email|max:100',
                    'billing_email_address'=> 'bail|nullable|email|max:100'
                ]);
                $requestData->replace($request->only(['main_email_address', 'support_email_address', 'billing_email_address']));
                break;
            case 'financialinformation':
                $validator = Validator::make($request->only(['bank_account', 'bank_identification_code', 'vat_number', 'vat_percentage']), [
                    'bank_account'=> 'bail|nullable|string|max:100',
                    'bank_identification_code'=> 'bail|nullable|string|max:100',
                    'vat_number'=> 'bail|nullable|string|max:100',
                    'vat_percentage'=> 'bail|nullable|required_with:vat_number|regex:/^\d*(\.\d{1,2})?$/|max:8'
                ]);
                $requestData->replace($request->only(['bank_account', 'bank_identification_code', 'vat_number', 'vat_percentage']));
                break;
            case 'notestocustomers':
                $validator = Validator::make($request->only('customer_note'), [
                    'customer_note'=> 'bail|nullable|string',
                ]);
                $requestData->replace($request->only('customer_note'));
                break;

            case 'smtpinformation':
                $validator = Validator::make($requestData = $request->only(['mail_host', 'mail_port', 'mail_username', 'mail_password']), [
                    'mail_driver'=> 'bail|nullable|string|max:20',
                    'mail_host'=> 'bail|nullable|string|max:100',
                    'mail_port'=> 'bail|nullable|integer',
                    'mail_username'=> 'bail|nullable|email|max:100',
                    'mail_password'=> 'bail|nullable|string|max:100'
                ]);
                $requestData->replace($request->only(['mail_host', 'mail_port', 'mail_username', 'mail_password']));
                break;
            case 'paypalinformation':
                $validator = Validator::make($request->only(['paypal_client_id', 'paypal_secret', 'paypal_currency_code']), [
                    'paypal_client_id'=> 'bail|nullable|string|max:200',
                    'paypal_secret'=> 'bail|nullable|string|max:200',
                    'paypal_currency_code'=> 'bail|nullable|string|max:10'
                ]);
                $requestData->replace($request->only(['paypal_client_id', 'paypal_secret', 'paypal_currency_code']));
                break;
            case 'stripeinformation':
                $validator = Validator::make($request->only(['stripe_key', 'stripe_secret']), [
                    'stripe_key'=> 'bail|nullable|string|max:200',
                    'stripe_secret'=> 'bail|nullable|string|max:200',
                ]);
                $requestData->replace($request->only(['id', 'stripe_key', 'stripe_secret']));
                break;
            default:
                break;
        }

        if ($validator->fails()) {
			$request->current_tab = 'nameandaddress';
            return redirect(url('admin/company/create#'.$request->current_tab))
                        ->withErrors($validator)
                        ->withInput();
        }

        try{
            $redirect_location = parent::storeCrud($requestData);
            $company = $this->crud->entry;
			//$company->rating = $request-> rating;
			//$company->more_info = $request->more_info;

            if($request->hasFile('file')){
                if($request->file('file')->isValid()){
                    $file = $request->file('file');
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('/uploads/logo'), $filename);
                    $company->logo = $filename;
                }
            }
			 $company->save();
            /* register company*/
			if($company->owner == NULL){
				if($company->name && $company->main_email_address && $company->address_line_1 && $company->town && $company->country && $company->domain_link){
					$companyUser = new \App\User();
					$companyUser->company_id = $company->id;
					$companyUser->tuning_credit_group_id = Null;
					$companyUser->first_name = $request->name;
					$companyUser->last_name = $request->name;
					$companyUser->lang = 'en';
					$companyUser->email = $request->main_email_address;
					$companyUser->business_name = $request->name;
					$companyUser->address_line_1 = $request->address_line_1;
					$companyUser->address_line_2 = $request->address_line_2;
					$companyUser->county = $request->country;
					$companyUser->town = $request->town;
					$companyUser->post_code = $request->post_code;
					$companyUser->is_master = 0;
					$companyUser->is_admin = 1;
					if($companyUser->save()){

                        $emailTemplates = \App\Models\EmailTemplate::where('company_id', $this->company->id)
                            ->whereIn('label', [
                                'customer-welcome-email',
                                'new-file-service-created-email',
                                'file-service-modified-email',
                                'file-service-processed-email',
                                'new-ticket-created',
                                'new-file-ticket-created',
                                'reply-to-your-ticket',
                                'file-service-upload-limited'
                            ])->get();

						if($emailTemplates->count() > 0){
							foreach($emailTemplates as $emailTemplate){
								$userTemplate = $emailTemplate->replicate();
								$userTemplate->company_id = $company->id;
								$userTemplate->save();
							}
						}
						$token = app('auth.password.broker')->createToken($companyUser);
						try{
							\Mail::to($companyUser->email)->send(new WelcomeCustomer($companyUser, $token));
						}catch(\Exception $e){
							\Alert::error('Error in SMTP: '.__('admin.opps'))->flash();
						}
					}
				}
			}
            if(!($company->name && $company->address_line_1 && $company->town && $company->country)){
                \Alert::warning('Please update company name and address in order to complete company registration.')->flash();
                return redirect('admin/company/'.$company->id.'/edit#nameandaddress');
            }

            if(!$company->domain_link){
                \Alert::warning('Please update company domain in order to complete company registration.')->flash();
                return redirect('admin/company/'.$company->id.'/edit#domaininformation');
            }

            if(!$company->main_email_address){
                \Alert::warning('Please update company main email address in order to complete company registration.')->flash();
                return redirect('admin/company/'.$company->id.'/edit#emailaddress');
            }

            return $redirect_location;
        }catch(\Exception $e){
			//dd($e);
            \Alert::error(__('admin.opps'))->flash();
        }
        //return redirect(backpack_url('company'));
    }

    /**
     * Update resource
     * @param Request $request
     * @return $response
     */
    public function update(Request $request){
        $requestData = \Illuminate\Http\Request::capture();
        switch($request->current_tab){
            case 'nameandaddress':
                $validator = Validator::make($request->only(['name', 'country', 'state', 'town', 'address_line_1', 'address_line_2', 'post_code', 'logo', 'theme_color', 'copy_right_text']),[
                    'name' => 'bail|required|string|max:100',
                    'address_line_1'=> 'bail|required|string|max:100',
                    'address_line_2'=> 'bail|nullable|string|max:100',
                    'town'=> 'bail|required|string|max:50',
                    'post_code'=> 'bail|nullable|string|max:30',
                    'country'=> 'bail|required|string|max:50',
                    'state'=> 'bail|nullable|string|max:50',
					//'rating'=> 'bail|nullable|string|max:50',
                    'file'=> 'bail|nullable|image|mimes:jpg,png,jpeg',
                    'copy_right_text'=> 'bail|nullable|string|max:100'
                ]);
                $requestData->replace($request->only(['id', 'name', 'country', 'state', 'town', 'address_line_1', 'address_line_2', 'post_code', 'logo', 'theme_color','copy_right_text']));

                break;
            case 'domaininformation':
                $validator = Validator::make($request->only('domain_link'), [
                    'domain_link'=> 'bail|required|url|unique:companies,domain_link,'.$request->id.',id|max:100'
                ]);
                $requestData->replace($request->only(['id', 'domain_link']));
                break;
            case 'emailaddress':
                $validator = Validator::make($request->only(['main_email_address', 'support_email_address', 'billing_email_address']), [
                    'main_email_address'=> 'bail|required|email|unique:companies,main_email_address,'.$request->id.',id|unique:users,email,'.$request->id.',company_id|max:100',
                    'support_email_address'=> 'bail|nullable|email|max:100',
                    'billing_email_address'=> 'bail|nullable|email|max:100'
                ]);

                  $requestData->replace($request->only(['id', 'main_email_address', 'support_email_address', 'billing_email_address']));
                break;
            case 'financialinformation':
                $validator = Validator::make($request->only(['bank_account', 'bank_identification_code', 'vat_number', 'vat_percentage']), [
                    'bank_account'=> 'bail|nullable|string|max:100',
                    'bank_identification_code'=> 'bail|nullable|string|max:100',
                    'vat_number'=> 'bail|nullable|string|max:100',
                    'vat_percentage'=> 'bail|nullable|required_with:vat_number|regex:/^\d*(\.\d{1,2})?$/|max:8'
                ]);
                 $requestData->replace($request->only(['id', 'bank_account', 'bank_identification_code', 'vat_number', 'vat_percentage']));
                break;
            case 'notestocustomers':
                $validator = Validator::make($request->only('customer_note'), [
                    'customer_note'=> 'bail|nullable|string',
                ]);
                $requestData->replace($request->only(['id', 'customer_note']));
                break;
            case 'smtpinformation':
                $validator = Validator::make($requestData = $request->only(['mail_host', 'mail_port', 'mail_username', 'mail_password']), [
                    'mail_driver'=> 'bail|nullable|string|max:20',
                    'mail_host'=> 'bail|nullable|string|max:100',
                    'mail_port'=> 'bail|nullable|integer',
                    'mail_username'=> 'bail|nullable|email|max:100',
                    'mail_password'=> 'bail|nullable|string|max:100'
                ]);
                $requestData->replace($request->only(['id', 'mail_host', 'mail_port', 'mail_username', 'mail_password']));
                break;
            case 'paypalinformation':
                $validator = Validator::make($request->only(['paypal_client_id', 'paypal_secret', 'paypal_currency_code']), [
                    'paypal_client_id'=> 'bail|nullable|string|max:200',
                    'paypal_secret'=> 'bail|nullable|string|max:200',
                    'paypal_currency_code'=> 'bail|nullable|string|max:10'
                ]);
                $requestData->replace($request->only(['id', 'paypal_client_id', 'paypal_secret', 'paypal_currency_code']));
                break;
            case 'stripeinformation':
                $validator = Validator::make($request->only(['stripe_key', 'stripe_secret']), [
                    'stripe_key'=> 'bail|nullable|string|max:200',
                    'stripe_secret'=> 'bail|nullable|string|max:200',
                ]);
                $requestData->replace($request->only(['id', 'stripe_key', 'stripe_secret']));
                break;
            default:
                break;
        }

        if ($validator->fails()) {
			$request->current_tab = 'nameandaddress';
            return redirect(url('admin/company/'.$request->id.'/edit#'.$request->current_tab))
                        ->withErrors($validator)
                        ->withInput();
        }

        $redirect_location = parent::updateCrud($requestData);

        $company = $this->crud->entry;
		//$company->rating = $request-> rating;
		//$company->more_info = $request->more_info;
        if($request->hasFile('file')){
            if($request->file('file')->isValid()){
                $file = $request->file('file');
                $filename = time() . '.' . $file->getClientOriginalExtension();


				$file = $file->move(public_path('/uploads/logo'), $filename);
				//dd($file);
                $company->logo = $filename;
            }
        }
		$company->save();

        if($company->owner == NULL){
            /* register company*/
            if($company->name && $company->main_email_address && $company->address_line_1 && $company->town && $company->country && $company->domain_link){

                $companyUser = new \App\User();
                $companyUser->company_id = $company->id;
                $companyUser->tuning_credit_group_id = Null;
                $companyUser->first_name = $request->name;
                $companyUser->last_name = $request->name;
                $companyUser->lang = 'en';
                $companyUser->email = $request->main_email_address;
                $companyUser->business_name = $request->name;
                $companyUser->address_line_1 = $request->address_line_1;
                $companyUser->address_line_2 = $request->address_line_2;
                $companyUser->county = $request->country;
                $companyUser->town = $request->town;
                $companyUser->post_code = $request->post_code;
                $companyUser->is_master = 0;
                $companyUser->is_admin = 1;
                if($companyUser->save()){

                    $emailTemplates = \App\Models\EmailTemplate::where('company_id', $this->company->id)->whereIn('label', ['customer-welcome-email', 'new-file-service-created-email', 'file-service-modified-email', 'file-service-processed-email','new-ticket-created','new-file-ticket-created','reply-to-your-ticket'])->get();

                    if($emailTemplates->count() > 0){
                        foreach($emailTemplates as $emailTemplate){
                            $userTemplate = $emailTemplate->replicate();
                            $userTemplate->company_id = $company->id;
                            $userTemplate->save();
                        }
                    }
                    $token = app('auth.password.broker')->createToken($companyUser);
					try{
						\Mail::to($companyUser->email)->send(new WelcomeCustomer($companyUser, $token));
					}catch(\Exception $e){
						\Alert::error('Error in SMTP: '.__('admin.opps'))->flash();
					}
                }
            }
        }else{
            $companyOwner = $company->owner;
            $companyOwner->email = $request->main_email_address;
            $companyOwner->save();
        }

        if(!($company->name && $company->address_line_1 && $company->town && $company->country)){
            \Alert::warning('Please update company name and address in order to complete company registration.')->flash();
            return redirect('admin/company/'.$company->id.'/edit#nameandaddress');
        }

        if(!$company->domain_link){
            \Alert::warning('Please update company domain in order to complete company registration.')->flash();
            return redirect('admin/company/'.$company->id.'/edit#domaininformation');
        }

        if(!$company->main_email_address){
            \Alert::warning('Please update company main email address in order to complete company registration.')->flash();
            return redirect('admin/company/'.$company->id.'/edit#emailaddress');
        }

        return $redirect_location;
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return string
     */
    public function destroy($id)
    {
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $entry = $this->crud->getEntry($id);
        if($this->company->is_default && ($this->company->id == $entry->id)){
            return redirect(url('admin/company'));
        }else{
            return $this->crud->delete($id);
        }
    }

    /**
     * Resend password reset link
     * @param App\User $user
     * @return $response
     */
    public function resendPasswordResetLink(\App\Models\Company $company){
        try{
            $user = $company->owner;
            $token = app('auth.password.broker')->createToken($user);
            \Mail::to($user->email)->send(new WelcomeCustomer($user, $token));
            \Alert::success(__('admin.password_reset_link_send'))->flash();
        }catch(\Exception $e){
            \Alert::error('Error in SMTP: '.__('admin.opps'))->flash();
        }
        return redirect(url('admin/company'));
    }

    /**
     * show company subscriptions
     * @param \App\Models\Company $company
     * @return $response
     */
    public function trialSubscriptions(\App\Models\Company $company){
        try{
            $data['title'] = "Free subscriptions for ".$company->name;
            $data['_company'] = $company;
            $data['_user'] = $company->owner;
            $data['_subscription'] = $company->owner->subscriptions->sortByDesc('id');
            return view('vendor.custom.common.company.subscription', $data);
        }catch(\Exception $e){
            \Alert::error(__('admin.opps'))->flash();
            return redirect(url('admin/comapny'))->withInput($company);
        }
    }

    /**
     * show company subscription
     * @param App\Http\Request\TransactionRequest $request
     * @return $response
     */
    public function storeTrialSubscription(\App\Http\Requests\SubscriptionRequest $request, \App\Models\Company $company){
       try{
           $validations = Validator::make($request->all(), [
                'description' => 'max:191',
                'trial_days' => 'required|numeric'
            ]);
           if(!$validations->fails()){
                $subscription = new \App\Models\Subscription();
                $subscription->user_id = $company->owner->id;
                $subscription->start_date = date("Y-m-d h:i:s");
                $subscription->trial_days = $request['trial_days'];
                $subscription->description = $request['description'];
                $subscription->pay_agreement_id = 'TRIAL'.rand(11111111, 99999999);
                $subscription->status = 'Active';
                $subscription->is_trial = 1;
                if($subscription->save()){
                    \Alert::success(__('admin.subscription_saved'))->flash();
                    return redirect(url('admin/subscription?company='.$company->id))->withInput($request->all());
                }else{
                    \Alert::error(__('admin.opps'))->flash();
                    return redirect()->back()->withInput($request->all());
                }
           }else{
               return redirect()->back()->withInput($request->all())->withErrors($validations->errors());
           }
        }catch(\Exception $e){
            \Alert::error(__('admin.opps'))->flash();
            return redirect()->back()->withInput($request->all());
        }

    }
}
