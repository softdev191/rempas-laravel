<?php

namespace App\Http\Controllers\Customer;

use App\Mail\FileServiceCreated;
use App\Mail\FileServiceLimited;
use App\Http\Controllers\MasterController;
use App\Http\Requests\FileServiceRequest as StoreRequest;
use App\Http\Requests\FileServiceRequest as UpdateRequest;
use App\Mail\TicketCreated;
use App\Mail\TicketReply;
/**
 * Class FileServiceCrudController
 * @param App\Http\Controllers\Customer
 * @property-read CrudPanel $crud
 */

class FileServiceCrudController extends MasterController
{

    public function setup()
    {

		/*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\FileService');
        $this->crud->setRoute('customer/file-service');
        $this->crud->setEntityNameStrings('file service', __('customer_msg.menu_FileServices'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addButtonFromView('line', 'download_orginal', 'download_orginal' , 'end');
        $this->crud->addButtonFromView('line', 'download_modified', 'download_modified' , 'end');
        $this->crud->addButtonFromView('line','file_service_ticket','file_service_ticket','end');
        $this->crud->setEditView('vendor.custom.customer.file_service.index');
        $this->crud->enableExportButtons();

		$this->crud->removeButton('delete');
        $this->crud->denyAccess('delete');
        $open_status = $this->open_status();
        if ($open_status == 2) {
            $this->crud->denyAccess('create');
            $this->crud->denyAccess('update');
        }

        $user = \Auth::guard('customer')->user();

        $this->crud->query->where('user_id', $user->id);
        if(\Request::query('status')){
            $this->crud->addClause('where', 'status', \Request::query('status'));
        }
        $this->crud->query->orderBy('id', 'DESC');

        /*
        |--------------------------------------------------------------------------
        | Basic Crud filter Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addFilter([
            'name' => 'status',
            'type' => 'dropdown',
            'label' => 'Status'
            ], config('site.file_service_staus'), function($value) {
                $this->crud->addClause('WHERE', 'status', $value);
        });
        $this->crud->addFilter([
            'type' => 'date_range',
            'name' => 'created_at',
            'label' => 'Date range'
            ],
            false,
            function($value) {
                $dates = json_decode($value);
                $this->crud->query->whereDate('created_at','>=', $dates->from);
                $this->crud->query->whereDate('created_at','<=', $dates->to);
        });

        /*
        |--------------------------------------------------------------------------
        | Basic Crud column Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
            'name' => 'displayable_id',
            'label' =>  __('customer_msg.tb_header_JobNo')
        ]);

        $this->crud->addColumn([
            'name' => 'car',
            'label' => __('customer_msg.tb_header_Car'),
            'searchLogic' => function ($query, $column, $searchTerm) {
                $searchWords = explode(' ', $searchTerm);
                if(count($searchWords)){
                    foreach ($searchWords as $key => $searchWord) {
                        $query->orWhereRaw("concat(make, ' ', model, ' ', generation) like '%$searchWord%' ");
                    }
                }
            }
        ]);

        $this->crud->addColumn([
            'name' => 'license_plate',
            'label' => __('customer_msg.tb_header_License')
        ]);

        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => __('customer_msg.tb_header_CreatedAt')
        ]);

        /*
        |--------------------------------------------------------------------------
        | Basic Crud Field Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addField([
            'name' => 'user_id',
            'type' => 'hidden',
            'value' => $user->id,
        ]);


        $this->crud->addField([
            'name' => 'make',
            'label' => "Make",
            'type' => 'text',
            'attributes'=>['placeholder'=>'Make'],
            'wrapperAttributes'=>['class'=>'form-group col-md-2 col-xs-12']
        ]);

        $this->crud->addField([
            'name' => 'model',
            'label' => "Model",
            'type' => 'text',
            'attributes'=>['placeholder'=>'Model'],
            'wrapperAttributes'=>['class'=>'form-group col-md-2 col-xs-12']
        ]);

        $this->crud->addField([
            'name' => 'generation',
            'label' => "Generation",
            'type' => 'text',
            'attributes'=>['placeholder'=>'Generation'],
            'wrapperAttributes'=>['class'=>'form-group col-md-2 col-xs-12']
        ]);

        $this->crud->addField([
            'name' => 'engine',
            'label' => "Engine",
            'type' => 'text',
            'attributes'=>['placeholder'=>'Engine'],
            'wrapperAttributes'=>['class'=>'form-group col-md-2 col-xs-12']
        ]);

        $this->crud->addField([
            'name' => 'ecu',
            'label' => "ECU",
            'type' => 'text',
            'attributes'=>['placeholder'=>'ECU'],
            'wrapperAttributes'=>['class'=>'form-group col-md-2 col-xs-12']
        ]);

        $this->crud->addField([
            'name' => 'engine_hp',
            'label' => "Engine HP <small class='text-muted'>(optional)</small>",
            'type' => 'number',
            'hint' => 'Please fill in at least one of the above engine values.',
            'attributes'=>['placeholder'=>'Engine HP'],
            'wrapperAttributes'=>['class'=>'form-group col-md-5 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank1',
            'type' => 'blank',
        ]);

        $this->crud->addField([
            'name' => 'year',
            'label' => "Year of Manufacture",
            'type' => 'select2_from_array',
            'options'=> range(1990, date('Y')),
            'allows_null' => false,
            'wrapperAttributes'=>['class'=>'form-group col-md-5 col-xs-12']
        ]);

        $this->crud->addField([
            'name' => 'gearbox',
            'label' => "Gearbox",
            'type' => 'select_from_array',
            'options'=> config('site.file_service_gearbox'),
            'allows_null' => false,
            'wrapperAttributes'=>['class'=>'form-group col-md-5 col-xs-12']
        ]);

		$this->crud->addField([
            'name' => 'fuel_type',
            'label' => "Fuel Type",
            'type' => 'select_from_array',
            'options'=> config('site.file_service_fuel_type'),
            'allows_null' => false,
            'wrapperAttributes'=>['class'=>'form-group col-md-5 col-xs-12']
        ]);

        $this->crud->addField([
            'name' => 'reading_tool',
            'label' => "Reading Tool",
            'type' => 'select_from_array',
            'options'=> config('site.file_service_reading_tool'),
            'allows_null' => false,
            'wrapperAttributes'=>['class'=>'form-group col-md-5 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank2',
            'type' => 'blank',
        ]);

        $this->crud->addField([
            'name' => 'license_plate',
            'type' => 'text',
            'label' => "License plate",
            'attributes'=>['placeholder'=>'License plate'],
            'wrapperAttributes'=>['class'=>'form-group col-md-5 col-xs-12']
        ]);


        $this->crud->addField([
            'name' => 'vin',
            'type' => 'text',
            'label' => "Miles / KM <small class='text-muted'>(optional)</small>",
            'attributes'=>['placeholder'=>''],
            'wrapperAttributes'=>['class'=>'form-group col-md-5 col-xs-12']
        ]);

        $this->crud->addField([
            'name'=> 'blank3',
            'type' => 'blank',
        ]);

        $this->crud->addField([
            'name' => 'note_to_engineer',
            'type' => 'textarea',
            'label' => "Note to engineer<small class='text-muted'>(optional)</small>",
            'attributes'=>['placeholder'=>'Note to engineer'],
            'wrapperAttributes'=>['class'=>'form-group col-md-5 col-xs-12']
        ]);

        $this->crud->addField([
            'name' => 'tuning_type_id',
            'label' => "Tuning type",
            'type' => 'ajax_select_tuning_type_options',
            'options'=> \App\Models\TuningType::where('company_id', $user->company_id)->orderBy('order_as', 'ASC')->pluck('label', 'id')->toArray(),
            'allows_null' => false,
            'attributes'=>['id'=>'tuningType'],
            'wrapperAttributes'=>['class'=>'form-group col-md-5 col-xs-12']
        ], 'create');

        $this->crud->addField([
            'name'=> 'blank4',
            'type' => 'blank',
        ]);

        $this->crud->addField([
            'name' => 'file',
            'label' => "File",
            'type' => 'file',
            'url'  => url('customer/upload-file-service-file'),
            'upload' => true,
            'wrapperAttributes'=>['class'=>'form-group col-md-10 col-xs-12']
        ], 'create');

        $this->crud->addField([
            'name' => 'status',
            'type' => 'hidden',
            'value' => $open_status == 1 ? 'P' : 'O'
        ]);

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    /**
     * Store resource
     * @param App\Http\Request\StoreRequest $request
     * @return $response
     */
    public function store(StoreRequest $request)
    {
        $open_status = $this->open_status();
        try{
            if ($open_status == 1) { // allow file service
                \Alert::warning('File Services are closed.')->flash();
            } else if ($open_status == 2) { // deny file service
                \Alert::warning('File Services are closed.')->flash();
                return redirect(url('customer/file-service'));
            }
            $redirect_location = parent::storeCrud($request);
            $fileService = $this->crud->entry;
            $fileService->year = 1990 + $fileService->year;
            if($fileService){
                if($request->uploaded_file != null){
                    $fileService->orginal_file = $request->uploaded_file;
                    $fileService->save();
                }
                $tuningTypeCredits = $fileService->tuningType->credits;

                if($request->has('tuning_type_options')){
                    $fileService->tuningTypeOptions()->sync($request->tuning_type_options);
                    $tuningTypeOptionsCredits = $fileService->tuningTypeOptions()->sum('credits');
                    $tuningTypeCredits = ($tuningTypeCredits+$tuningTypeOptionsCredits);
                }

                /* save user credits */
                $user = $fileService->user;
                $totalCredits = ($user->tuning_credits-$tuningTypeCredits);
                $user->tuning_credits = $totalCredits;
                $user->save();

                /* save file service displayable id */
                $displableId = \App\Models\FileService::wherehas('user', function($query) use($user){
                    $query->where('company_id', $user->company_id);
                })->max('displayable_id');
                $displableId++;
                $fileService->displayable_id = $displableId;
                $fileService->save();

                /* save transaction */
                $transaction = new \App\Models\Transaction();
                $transaction->user_id = $user->id;
                $transaction->credits = number_format($tuningTypeCredits, 2);
                $transaction->description = "File Service: ".$fileService->car;
                $transaction->status = config('site.transaction_status.completed');
                $transaction->type    = 'S';
                $transaction->save();
                try{
                    if ($open_status == -1) {
                        \Mail::to($this->company->owner->email)->send(new FileServiceCreated($fileService));
                    } else if ($open_status == 1) {
                        \Mail::to($user->email)->send(new FileServiceLimited($fileService));
                    }
                }catch(\Exception $e){
                    \Alert::error('Error in SMTP: '.__('admin.opps'))->flash();
                }
            }
            return redirect(url('customer/dashboard'));
        }catch(\Exception $e){
            \Alert::error($e->getMessage())->flash();
            return redirect(url('customer/file-service'));
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

        if($this->user->id != $entry->user->id){
            abort(403, __('customer.no_permission'));
        }
        // dd( $this->crud->getUpdateFields($id));
        $data['entry'] = $entry;
        $data['crud'] = $this->crud;
        $data['saveAction'] = $this->getSaveAction();
        $data['fields'] = $this->crud->getUpdateFields($id);
        $data['title'] = trans('backpack::crud.edit').' '.$this->crud->entity_name;
        $data['id'] = $id;

        return view($this->crud->getEditView(), $data);
    }

    /**
     * Upload file
     * @param \Illuminate\Http\Request $request
     * @return $response
     */
    public function uploadFile(\Illuminate\Http\Request $request){
        if($request->hasFile('file')){
            if($request->file('file')->isValid()){
                $file = $request->file('file');
                $ext = $file->getClientOriginalExtension();
                if(!isset($ext)){
                    $filename = time() . '.dat';
                }else{
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                }

                if($file->move(public_path('uploads/file-services/orginal'), $filename)){
                    return response()->json(['status'=> TRUE, 'file'=>$filename], 200);
                }else{
                    return response()->json(['status'=> FALSE, 'msg'=>'File shouldn\'t be greater than 10 MB. Please select another file.'], 404);
                }
            }else{
                return response()->json(['status'=> FALSE, 'msg'=>'File shouldn\'t be greater than 10 MB. Please select another file.'], 404);
            }
        }else{
            return response()->json(['status'=> FALSE, 'msg'=>'File shouldn\'t be greater than 10 MB. Please select another file.'], 404);
        }
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
            /*$fileService = $this->crud->entry;
            if($request->uploaded_file != null){
                $fileService->orginal_file = $request->uploaded_file;
                $fileService->save();
            }*/
            return $redirect_location;
        }catch(\Exception $e){
            \Alert::error($e->getMessage())->flash();
            return redirect(url('customer/file-service'));
        }

    }

    /**
     * download orginal file
     * @param \App\Models\FileService $fileService
     * @return $response
     */
    public function downloadOrginalFile(\App\Models\FileService $fileService){
        try{
            $file = public_path('uploads/file-services/orginal/' . $fileService->orginal_file);
            if(\File::exists($file)){
                $fileExt = \File::extension($file);
                $fileName = $fileService->displayable_id.'-orginal.'.$fileExt;
                return response()->download($file, $fileName);
            }else{
                \Alert::error(__('customer.opps'))->flash();
            }
        }catch(\Exception $e){
            \Alert::error(__('customer.opps'))->flash();
        }
        return redirect('customer/file-service');

    }

    /**
     * download modified file
     * @param \App\Models\FileService $fileService
     * @return $response
     */
    public function downloadModifiedFile(\App\Models\FileService $fileService){
        try{
            $file = public_path('uploads/file-services/modified/' . $fileService->modified_file);
            if(\File::exists($file)){
                $fileExt = \File::extension($file);
                $fileName = $fileService->displayable_id.'-modified.'.$fileExt;
                return response()->download($file, $fileName);
            }else{
                \Alert::error(__('customer.opps'))->flash();
            }
        }catch(\Exception $e){
            \Alert::error(__('customer.opps'))->flash();
        }
        return redirect('customer/file-service');

    }

    /**
     * Create resource
     * @return $response
     */
    public function createTicket(\App\Models\FileService $fileService){

        $data['fileService'] = $fileService;
        $data['crud'] = $this->crud;
        $data['entry'] = $this->crud;
        return view('vendor.custom.common.file_service.ticket', $data);
    }
    /**
     * Store Ticket
     * @param \App\Http\Requests\TicketsRequest $request
     * @param \App\Models\FileService $fileService
     * @return $response
     */
    public function storeTicket(\App\Http\Requests\TicketsRequest $request, \App\Models\FileService $fileService){
        $ticket = new \App\Models\Tickets();
        $ticket->sender_id = $this->user->id;
        $ticket->receiver_id = $fileService->user->id;
		if($ticket->sender_id == $ticket->receiver_id) {
			$ticket->receiver_id = $this->user->company->owner->id;
		}
        $ticket->file_servcie_id = $request->file_servcie_id;
        $ticket->message = $request->message;
        if($request->uploaded_file != null){
            $ticket->document = $request->uploaded_file;
        }
        $ticket->is_closed = 0;
        $jobDetails=$fileService->make.' '.$fileService->model.' '.$fileService->generation;
        if($ticket->save()){
            try{
            	\Mail::to($this->user->company->owner->email)->send(new TicketCreated($this->user,$jobDetails));
			}catch(\Exception $e){
				\Alert::error('Error in SMTP: '.__('admin.opps'))->flash();
			}
            \Alert::success(__('admin.ticket_saved'))->flash();
        }else{
            \Alert::error(__('customer.opps'))->flash();
            return redirect()->back()->withInput($request->all());
        }
        return redirect('customer/file-service');
    }

    public function open_status() {
        $user = \Auth::guard('customer')->user();
        $company = $user->company;
        $day = lcfirst(date('l'));
        $daymark_from = substr($day, 0, 3).'_from';
        $daymark_to = substr($day, 0, 3).'_to';

        $open_status = -1;
        if ($company->open_check) {
            if ($company->$daymark_from && str_replace(':', '', $company->$daymark_from) > date('Hi')
                || $company->$daymark_to && str_replace(':', '', $company->$daymark_to) < date('Hi')) {
                $open_status = $company->notify_check == 0 ? 1 : 2;
            }
        }
        return $open_status;
    }
}
