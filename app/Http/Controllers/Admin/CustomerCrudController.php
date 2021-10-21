<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CustomerRequest as StoreRequest;
use App\Http\Requests\CustomerRequest as UpdateRequest;
use App\Http\Controllers\MasterController;
use App\Http\Requests\TransactionRequest;
use App\Mail\WelcomeCustomer;
use App\User;
use App\Models\Log;
/**
 * Class CustomerCrudController
 * @param App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CustomerCrudController extends MasterController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\User');
        $this->crud->setRoute('admin/customer');
        $this->crud->setEntityNameStrings('customers', 'customers');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addButtonFromView('line', 'customer_file_service', 'show_customer_file_service', 'end');
        $this->crud->addButtonFromView('line', 'switch_account', 'switch_account' , 'end');
        $this->crud->addButtonFromView('line', 'show_customer_transaction', 'show_customer_transaction' , 'end');
        $this->crud->addButtonFromView('line', 'resend_password_reset_link', 'resend_password_reset_link' , 'end');
        $this->crud->enableExportButtons();

        $user = \Auth::guard('admin')->user();
        $this->crud->query->where('company_id', $user->company_id);
        $this->crud->query->where('is_admin', 0);
        $this->crud->query->orderBy('id', 'DESC');

        /*
        |--------------------------------------------------------------------------
        | Basic Crud column Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
            'name' => 'full_name',
            'label' => __('customer_msg.tb_header_Name'),
            'class' => 'exportable'
        ]);

        $this->crud->addColumn([
            'name' => 'business_name',
            'label' => __('customer_msg.tb_header_Company'),
            'class' => 'exportable'
        ]);

        $this->crud->addColumn([
            'name' => 'user_tuning_credits',
            'label' => __('customer_msg.tb_header_TuningCredits'),
            'class' => 'exportable'
        ]);

        $this->crud->addColumn([
            'name' => 'tuning_price_group',
            'label' => __('customer_msg.tb_header_TuningPriceGroup'),
            'class' => 'exportable'
        ]);

        $this->crud->addColumn([
            'name' => 'file_services_count',
            'label' => __('customer_msg.tb_header_FileService'),
            'class' => 'exportable'
        ]);

        if ($user->company->reseller_id) {
            $this->crud->addColumn([
                'name' => 'user_evc_tuning_credits',
                'label' => 'EVC '.__('customer_msg.tb_header_TuningCredits'),
                'class' => 'exportable'
            ]);

            $this->crud->addColumn([
                'name' => 'tuning_evc_price_group',
                'label' => __('customer_msg.tb_header_EVCTuningPriceGroup'),
                'class' => 'exportable'
            ]);
        }

        $this->crud->addColumn([
            'name' => 'last_login',
            'label' => __('customer_msg.tb_header_Lastlogin'),
            'class' => 'exportable'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Basic Crud Field Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addField([
            'name' => 'private',
            'label' => __('customer_msg.contactInfo_Type'),
            'type' => 'select_from_array',
            'options' => [
                '0'=> 'Private Customer',
                '1'=> 'Business Customer',
            ],
            'allows_null' => false,
            'wrapperAttributes'=>['class'=>'form-group col-md-4 col-xs-12'],
            'attributes' => [
                'class' => 'form-control customer-type'
            ]
        ]);

        $this->crud->addField([
            'name' => 'vat_number',
            'type' => 'text',
            'label' => __('customer_msg.contactInfo_taxvat'),
            'attributes'=>['placeholder'=>'TAX/VAT Number'],
            'wrapperAttributes'=>['class'=>'form-group col-md-3 col-xs-12 col-tax'],
        ]);

        if (!empty($user->company->vat_number) && !empty($user->company->vat_percentage)) {
            $this->crud->addField([
                'name' => 'add_tax',
                'label' => __('customer_msg.contactInfo_addtax'),
                'type' => 'checkbox',
            ]);
        }

        $this->crud->addField([
            'name'=> 'blank1',
            'type' => 'blank',
        ]);

        $default_tier = \App\Models\TuningCreditGroup::where('company_id', $user->company_id)
            ->where('group_type', 'normal')
            ->where('set_default_tier', 1)
            ->first();

        $this->crud->addField([
            'name' => 'tuning_credit_group_id',
            'label' => __('customer_msg.contactInfo_TuningPriceType'),
            'type' => 'select2_from_array',
            'options' => \App\Models\TuningCreditGroup::where('company_id', $user->company_id)->where('group_type', 'normal')->orderBy('is_default', 'DESC')->pluck('name', 'id'),
            'default' => $default_tier ? $default_tier->id : 0,
            'allows_null' => true,
            'wrapperAttributes'=>['class'=>'form-group col-md-4 col-xs-12']
        ]);
        if ($user->company->reseller_id) {
            $this->crud->addField([
                'name' => 'tuning_evc_credit_group_id',
                'label' => __('customer_msg.contactInfo_EVCTuningPriceType'),
                'type' => 'select2_from_array',
                'options' => \App\Models\TuningCreditGroup::where('company_id', $user->company_id)->where('group_type', 'evc')->orderBy('is_default', 'DESC')->pluck('name', 'id'),
                'allows_null' => true,
                'wrapperAttributes'=>['class'=>'form-group col-md-4 col-xs-12']
            ]);
        }

        $this->crud->addField([
            'name'=> 'blank3',
            'type' => 'blank',
        ]);

        $this->crud->addField([
            'name' => 'lang',
            'label' => __('customer_msg.contactInfo_Language'),
            'type' => 'select_from_array',
            'options' => [
                'en'=> 'English',
                'fr'=> 'French',
                'es'=> 'Spanish',
                'pt'=> 'Portuguese',
                'it'=> 'Italian',
                'ja'=> 'Japanese',
                'nl'=> 'Dutch',
                'pl'=> 'Polish',
                'de'=> 'German',
                'ru'=> 'Russian',
                'tr'=> 'Turkish'
            ],
            'allows_null' => false,
            'wrapperAttributes'=>['class'=>'form-group col-md-4 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank4',
            'type' => 'blank',
        ]);

        $this->crud->addField([
            'name' => 'title',
            'label' => __('customer_msg.contactInfo_Title'),
            'type' => 'select_from_array',
            'options' => ['Mr'=> 'Mr', 'Ms' => 'Ms'],
            'allows_null' => false,
            'wrapperAttributes'=>['class'=>'form-group col-md-2 col-xs-12']
        ]);

        $this->crud->addField([
            'name' => 'first_name',
            'type' => 'text',
            'label' => __('customer_msg.contactInfo_FirstName'),
            'attributes'=>['placeholder'=>'First name'],
            'wrapperAttributes'=>['class'=>'form-group col-md-3 col-xs-12']
        ]);

        $this->crud->addField([
            'name' => 'last_name',
            'type' => 'text',
            'label' => __('customer_msg.contactInfo_LastName'),
            'attributes'=>['placeholder'=>'Last name'],
            'wrapperAttributes'=>['class'=>'form-group col-md-3 col-xs-12']
        ]);
        $this->crud->addField([
            'name'=> 'blank2',
            'type' => 'blank',
        ]);
        $this->crud->addField([
            'name' => 'business_name',
            'type' => 'text',
            'label' => __('customer_msg.contactInfo_BusinessName'),
            'attributes'=>['placeholder'=>'Business name'],
            'wrapperAttributes'=>['class'=>'form-group col-md-4 col-xs-12']
        ]);

        $this->crud->addField([
            'name' => 'email',
            'type' => 'text',
            'label' => __('customer_msg.contactInfo_Email'),
            'attributes'=>['placeholder'=>'Email'],
            'wrapperAttributes'=>['class'=>'form-group col-md-4 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank3',
            'type' => 'blank',
        ]);

        $this->crud->addField([
            'name' => 'address_line_1',
            'type' => 'text',
            'label' => __('customer_msg.contactInfo_AddressLine1'),
            'attributes'=>['placeholder'=>'Address line 1'],
            'wrapperAttributes'=>['class'=>'form-group col-md-4 col-xs-12']
        ]);

        $this->crud->addField([
            'name' => 'address_line_2',
            'type' => 'text',
            'label' => __('customer_msg.contactInfo_AddressLine2')."<small class='text-muted'>(".__('customer_msg.service_Optional').")</small>",
            'attributes'=>['placeholder'=>'Address line 2'],
            'wrapperAttributes'=>['class'=>'form-group col-md-4 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank4',
            'type' => 'blank',
        ]);

        $this->crud->addField([
            'name' => 'town',
            'type' => 'text',
            'label' => __('customer_msg.contactInfo_Town'),
            'attributes'=>['placeholder'=>'Town'],
            'wrapperAttributes'=>['class'=>'form-group col-md-3 col-xs-12']
        ]);

        $this->crud->addField([
            'name' => 'post_code',
            'type' => 'text',
            'label' => __('customer_msg.contactInfo_PostCode')."<small class='text-muted'>(".__('customer_msg.service_Optional').")</small>",
            'attributes'=>['placeholder'=>'Zip code'],
            'wrapperAttributes'=>['class'=>'form-group col-md-2 col-xs-12']
        ]);

        $this->crud->addField([
            'name' => 'county',
            'type' => 'text',
            'label' => __('customer_msg.contactInfo_County'),
            'attributes'=>['placeholder'=>'county'],
            'wrapperAttributes'=>['class'=>'form-group col-md-3 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank5',
            'type' => 'blank',
        ]);

        $this->crud->addField([
            'name' => 'phone',
            'type' => 'text',
            'label' => __('customer_msg.contactInfo_Phone'),
            'attributes'=>['placeholder'=>'Phone'],
            'wrapperAttributes'=>['class'=>'form-group col-md-4 col-xs-12']
        ]);

        $this->crud->addField([
            'name' => 'tools',
            'type' => 'textarea',
            'label' => __('customer_msg.contactInfo_Tools')."<small class='text-muted'>(".__('customer_msg.service_Optional').")</small>",
            'attributes'=>['placeholder'=>'Tools'],
            'wrapperAttributes'=>['class'=>'form-group col-md-4 col-xs-12']
        ]);

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    /**
     * Store resource
     * @package App\Http\Request\StoreRequest $request
     * @return $response
     */
    public function store(StoreRequest $request)
    {
        try{
            $request->request->add(['company_id'=> $this->company->id]);
            $redirect_location = parent::storeCrud($request);
            $user = $this->crud->entry;
            $token = app('auth.password.broker')->createToken($user);
            Log::create([
                'staff_id' => $user->id,
                'table' => 'Customer',
                'action' => 'CREATE',
                'id' => $this->crud->entry->id,
            ]);
			try{
            	\Mail::to($user->email)->send(new WelcomeCustomer($user, $token));
			}catch(\Exception $e){
				\Alert::error('Error in SMTP: '.__('admin.opps'))->flash();
			}
            return $redirect_location;
        }catch(\Exception $e){
            \Alert::error(__('admin.opps'))->flash();
            return redirect(url('admin/customer'));
        }

    }

    /**
     * Edit resource
     * @param (int) $id
     * @return $response
     */
    public function edit($id){
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $entry = $this->crud->getEntry($id);

        if($this->company->id != $entry->company->id){
            abort(403, __('admin.no_permission'));
        }

        $data['entry'] = $entry;
        $data['crud'] = $this->crud;
        $data['saveAction'] = $this->getSaveAction();
        $data['fields'] = $this->crud->getUpdateFields($id);
        $data['title'] = trans('backpack::crud.edit').' '.$this->crud->entity_name;
        $data['id'] = $id;

        return view($this->crud->getEditView(), $data);
    }

    /**
     * Update resource
     * @param App\Http\Request\UpdateRequest $request
     * @return $response
     */
    public function update(UpdateRequest $request)
    {
        try{
            $redirect_location = parent::updateCrud($request);
            Log::create([
                'staff_id' => $this->user->id,
                'table' => 'Customer',
                'action' => 'CREATE',
                'id' => $this->crud->entry->id,
            ]);
            return $redirect_location;
        }catch(\Exception $e){
            dd($e);
            \Alert::error(__('admin.opps'))->flash();
            return redirect(url('admin/customer'));
        }

    }

    /**
     * Resend password reset link
     * @param App\User $user
     * @return $response
     */
    public function resendPasswordResetLink(User $user){
        try{
            $token = app('auth.password.broker')->createToken($user);
			try{
				\Mail::to($user->email)->send(new WelcomeCustomer($user, $token));
			}catch(\Exception $e){

				\Alert::error('Error in SMTP: '.__('admin.opps'))->flash();
			}
            \Alert::success(__('admin.password_reset_link_send'))->flash();
        }catch(\Exception $e){
			\Alert::error(__('admin.opps'))->flash();
        }
        return redirect(url('admin/customer'));
    }

    /**
     * show customer transaction
     * @param App\User $user
     * @return $response
     */
    public function transactions(User $user){
        try{
            if($this->company->id != $user->company->id){
                abort(403, __('admin.no_permission'));
            }
            $data['title'] = "Transactions for ".$user->full_name;
            $data['transactions'] = $user->transactions()->orderBy('id', 'DESC')->take(20)->get();
            $data['customer'] = $user;
            $data['company'] = $user->company;
            return view('vendor.custom.common.customer.transactions', $data);
        }catch(\Exception $e){
            \Alert::error(__('admin.opps'))->flash();
            return redirect(backpack_url('customer'));
        }
    }

    /**
     * show customer transaction
     * @param App\Http\Request\TransactionRequest $request
     * @return $response
     */
    public function storeTransaction(TransactionRequest $request){
        try{
            $request->request->add(['status'=>'Completed']);
            $transaction = new \App\Models\Transaction($request->all());
            $user = $transaction->user;
            if($transaction->save()){

                if($transaction->type == 'A'){
                    $totalCredits = ($user->tuning_credits+$transaction->credits);
                }else{
                   $totalCredits = ($user->tuning_credits-$transaction->credits);
                }

                $user->tuning_credits = $totalCredits;
                $user->save();

                \Alert::success(__('admin.transaction_saved'))->flash();
            }else{
                \Alert::error(__('admin.opps'))->flash();
            }
            return redirect(url('admin/customer/'.$transaction->user->id.'/transactions'));
        }catch(\Exception $e){
            \Alert::error(__('admin.opps'))->flash();
        }
        return redirect(url('admin/customer'));
    }

    public function storeTransactionEVC(TransactionRequest $request) {
        try{
            $request->request->add(['status'=>'Completed']);
            $transaction = new \App\Models\Transaction($request->all());
            $user = $transaction->user;

            $url = "https://evc.de/services/api_resellercredits.asp";
            $dataArray = array(
                'apiid'=>'j34sbc93hb90',
                'username'=> $user->company->reseller_id,
                'password'=> $user->company->reseller_password,
                'verb'=>'addcustomeraccount',
                'customer' => $user->reseller_id,
                'credits' => $transaction->credits
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
            if (strpos($response, 'ok') !== FALSE) {
                $transaction->save();
            }
            return redirect(url('admin/customer/'.$transaction->user->id.'/transactions'));
        }catch(\Exception $e){
            \Alert::error(__('admin.opps'))->flash();
        }
        return redirect(url('admin/customer'));
    }

    /**
     * show customer transaction
     * @param App\Http\Request\TransactionRequest $request
     * @return $response
     */
    public function deleteTransaction(\App\Models\Transaction $transaction){
        try{
            if($transaction->delete()){
                \Alert::success(__('admin.transaction_deleted'))->flash();
            }else{
                \Alert::error(__('admin.opps'))->flash();
            }
            return redirect(url('admin/customer/'.$transaction->user->id.'/transactions'));
        }catch(\Exception $e){
            \Alert::error(__('admin.opps'))->flash();
        }
        return redirect(url('admin/customer'));
    }

    /**
     * show customer file-services
     * @param App\User $user
     * @return $response
     */
    public function fileServices(User $user){
        try{
            if($this->company->id != $user->company->id){
                abort(403, __('admin.no_permission'));
            }
            $data['title'] = "File services for ".$user->full_name;
            $data['fileServices'] = $user->fileServices()->orderBy('id', 'DESC')->take(20)->get();
            $data['customer'] = $user;
            return view('vendor.custom.common.customer.file_services', $data);
        }catch(\Exception $e){
            \Alert::error(__('admin.opps'))->flash();
            return redirect(url('admin/customer'));
        }
    }

    /**
     * delete customer file service
     * @param App\Http\Request\TransactionRequest $request
     * @return $response
     */
    public function deleteFileService(\App\Models\FileService $fileService){
        try{
            if($fileService->delete()){
                \Alert::success(__('admin.fileservice_deleted'))->flash();
            }else{
                \Alert::error(__('admin.opps'))->flash();
            }
            return redirect(url('admin/customer/'.$fileService->user->id.'/file-services'));
        }catch(\Exception $e){
            \Alert::error(__('admin.opps'))->flash();
        }
        return redirect(url('admin/customer'));
    }

    /**
     * Switch account from admin to customer.
     * @param \App\User $user
     * @return response()
     */
    public function switchAsCustomer(\App\User $user){
        try{
            \Auth::guard('customer')->login($user);
            return redirect()->away(url('customer/dashboard'));
        }catch(\Exception $e){
            \Alert::error(__('admin.opps'))->flash();
        }
        return redirect(url('admin/dashboard'));
    }

}
