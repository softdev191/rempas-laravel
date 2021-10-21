<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StaffRequest as StoreRequest;
use App\Http\Requests\StaffRequest as UpdateRequest;
use App\Http\Controllers\MasterController;
use App\Http\Requests\TransactionRequest;
use App\Mail\WelcomeCustomer;
use App\User;
/**
 * Class StaffCrudController
 * @param App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class StaffCrudController extends MasterController
{
    public function setup()
    {
        $this->crud->setModel('App\User');
        $this->crud->setRoute('admin/staff');
        $this->crud->setEntityNameStrings('staffs', 'staffs');

        $this->crud->addButtonFromView('line', 'switch_account', 'switch_account' , 'end');
        $this->crud->enableExportButtons();

        $user = \Auth::guard('admin')->user();
        $this->crud->query->where('company_id', $user->company_id);
        $this->crud->query->where('is_staff', 1);
        $this->crud->query->orderBy('id', 'DESC');

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
            'name' => 'last_login',
            'label' => __('customer_msg.tb_header_Lastlogin'),
            'class' => 'exportable'
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
            'name'=> 'blank1',
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
    }

    public function store(StoreRequest $request)
    {
        try{
            $request->request->add([
                'company_id' => $this->company->id,
                'is_staff' => 1,
            ]);
            $redirect_location = parent::storeCrud($request);
            $user = $this->crud->entry;
            $token = app('auth.password.broker')->createToken($user);
			// try{
            // 	\Mail::to($user->email)->send(new WelcomeCustomer($user, $token));
			// }catch(\Exception $e){
			// 	\Alert::error('Error in SMTP: '.__('admin.opps'))->flash();
			// }
            return $redirect_location;
        }catch(\Exception $e){
            dd($e);
            \Alert::error(__('admin.opps'))->flash();
            return redirect(url('admin/customer'));
        }
    }

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

    public function update(UpdateRequest $request)
    {
        try{
            $redirect_location = parent::updateCrud($request);
            return $redirect_location;
        }catch(\Exception $e){
            \Alert::error(__('admin.opps'))->flash();
            return redirect(url('admin/customer'));
        }
    }

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

    public function switchAsStaff(\App\User $user){
        try{
            // $user = $company->users()->where('is_master', 0)->where('is_admin', 1)->first();

            if($user){
                \Auth::guard('admin')->login($user);
                return redirect()->away($user->company->domain_link.'/admin/dashboard');
            }
        }catch(\Exception $e){
            \Alert::error(__('admin.opps'))->flash();
        }
        abort(404, __('admin.opps'));
    }
}
