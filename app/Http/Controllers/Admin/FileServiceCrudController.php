<?php

namespace App\Http\Controllers\Admin;

use App\Mail\FileServiceModified;
use App\Mail\FileServiceProcessed;
use App\Http\Controllers\MasterController;
use App\Http\Requests\AdminFileServiceRequest as StoreRequest;
use App\Http\Requests\AdminFileServiceRequest as UpdateRequest;
use App\Mail\TicketFileCreated;
use App\Models\FileService;
/**
 * Class FileServiceCrudController
 * @param App\Http\Controllers\Admin
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
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/file-service');
        $this->crud->setEntityNameStrings('file service', 'file services');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->removeButton('create');
        $this->crud->addButtonFromView('line','file_service_ticket','file_service_ticket','end');
        $this->crud->denyAccess('create');
        $this->crud->enableExportButtons();
        $this->crud->setEditView('vendor.custom.common.file_service.index');

        $user = \Auth::guard('admin')->user();
        $this->crud->query->whereHas('user', function($query) use($user){
            return $query->where('company_id', $user->company_id);
        });

        FileService::whereHas('user', function($query) use($user){
            $query->where('company_id', $user->company_id);
        })->where('status', 'P')->update(['status' => 'O']);

        $this->crud->query->orderBy('id', 'DESC');
        if(\Request::query('status')){
            $this->crud->addClause('where', 'status', \Request::query('status'));
        }
        /*
        |--------------------------------------------------------------------------
        | Basic Crud filter Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addFilter([
            'name' => 'business_name',
            'type' => 'select2',
            'label' => 'Company'
                ], function() use($user){
            return \App\User::customer()->where('company_id', $user->company_id)->distinct('business_name')->pluck('business_name', 'business_name')->toArray();
        }, function($value) {
            $this->crud->query->whereHas('user', function($query) use($value){
                return $query->where('business_name', 'LIKE', '%'.$value.'%');
            });
        });

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
            'label' => __('customer_msg.tb_header_JobNo')
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
        $redirect_location = parent::storeCrud($request);
        return $redirect_location;
    }

    /**
     * Edit resource
     * @param (int) $id
     * @return $response
     */
    public function edit($id){
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $entry = $this->crud->getEntry($id);

        if($this->company->id != $entry->user->company->id){
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
     * @param \App\Models\FileService $fileService
     * @return $response
     */
    public function update(UpdateRequest $request)
    {
        $redirect_location = parent::updateCrud($request);
        $fileService = $this->crud->entry;
        if($request->uploaded_file != null){
            $fileService->modified_file = $request->uploaded_file;
            if($request->status != 'C'){
                $fileService->status = 'W';
            }else{
				try{
					\Mail::to($fileService->user->email)->send(new FileServiceModified($fileService));
				}catch(\Exception $e){
					\Alert::error('Error in SMTP: '.__('admin.opps'))->flash();
				}
			}
            $fileService->save();
        }
        /*if($fileService->wasChanged('status')){
            try{
            	\Mail::to($fileService->user->email)->send(new FileServiceModified($fileService));
			}catch(\Exception $e){
				\Alert::error('Error in SMTP: '.__('admin.opps'))->flash();
			}
        }*/

        return $redirect_location;

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
                $filename = time() . '.' . $file->getClientOriginalExtension();
                if($file->move(public_path('uploads/file-services/modified'), $filename)){
                    return response()->json(['status'=> TRUE, 'file'=>$filename], 200);
                }else{
                    return response()->json(['status'=> FALSE], 404);
                }
            }else{
                return response()->json(['status'=> FALSE], 404);
            }
        }else{
            return response()->json(['status'=> FALSE], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return string
     */
    public function destroy($id)
    {
        try{
            $fileService = \App\Models\FileService::find($id);
            $fileServiceUser = $fileService->user;
            if($fileService->status != 'Completed'){
                $tuningTypeCredits = $fileService->tuningType->credits;
                $tuningTypeOptionsCredits = $fileService->tuningTypeOptions()->sum('credits');
                $fileServicecredits = ($tuningTypeCredits+$tuningTypeOptionsCredits);
                $usersCredits = ($fileServiceUser->tuning_credits+$fileServicecredits);
            }else{
                $usersCredits = $fileServiceUser->tuning_credits;
            }
            $redirect_location = parent::destroy($id);

            $fileServiceUser->tuning_credits = $usersCredits;
            $fileServiceUser->save();
            return $redirect_location;
        }catch(\Exception $e){
            \Alert::error(__('admin.opps'))->flash();
            return redirect('admin/file-service');
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
                try{
					\Mail::to($fileService->user->email)->send(new FileServiceProcessed($fileService));
				}catch(\Exception $e){
					\Alert::error('Error in SMTP: '.__('admin.opps'))->flash();
				}

                return response()->download($file, $fileName);
            }else{
                \Alert::error(__('admin.opps'))->flash();
            }
        }catch(\Exception $e){

            \Alert::error(__('admin.opps'))->flash();
        }
        return redirect('admin/file-service');

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
                \Alert::error(__('admin.opps'))->flash();
            }
        }catch(\Exception $e){
            \Alert::error(__('admin.opps'))->flash();
        }
        return redirect('admin/file-service');

    }

    /**
     * delete modified file
     * @param \App\Models\FileService $fileService
     * @return $response
     */
    public function deleteModifiedFile(\App\Models\FileService $fileService){
        try{

            if(\File::exists(public_path('uploads/file-services/modified/' . $fileService->modified_file))){
                \File::delete(public_path('uploads/file-services/modified/' . $fileService->modified_file));
            }
            $fileService->modified_file = "";
            $fileService->save();
            \Alert::success(__('admin.modified_file_deleted'))->flash();
        }catch(\Exception $e){

            \Alert::error(__('admin.opps'))->flash();
        }

        return redirect('admin/file-service/'.$fileService->id.'/edit');
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
     * storeTicket resource
     * @param App\Http\Request\TicketsRequest $request
     * @return $response
     */
    public function storeTicket(\App\Http\Requests\TicketsRequest $request, \App\Models\FileService $fileService)
    {
        $ticket = new \App\Models\Tickets();
        $ticket->sender_id = $this->user->id;
        $ticket->receiver_id = $fileService->user->id;
        $ticket->file_servcie_id = $request->file_servcie_id;
        $ticket->message = $request->message;
        if($request->uploaded_file != null){
            $ticket->document = $request->uploaded_file;
        }
        $ticket->is_closed = 0;
		$jobDetails = $fileService->make.' '.$fileService->model.' '.$fileService->generation;
        if($ticket->save()){
			$user = \App\User::find($ticket->receiver_id);
			try{
            	\Mail::to($user->email)->send(new TicketFileCreated($user, $jobDetails));
			}catch(\Exception $e){
				\Alert::error('Error in SMTP: '.__('admin.opps'))->flash();
			}
            \Alert::success(__('admin.ticket_saved'))->flash();
        }else{
            \Alert::error(__('admin.opps'))->flash();
            return redirect()->back()->withInput($request->all());
        }
        return redirect('admin/file-service');
    }
}
