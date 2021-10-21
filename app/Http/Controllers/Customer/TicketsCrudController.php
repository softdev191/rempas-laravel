<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\MasterController;
use App\Http\Requests\TicketsRequest as StoreRequest;
use App\Http\Requests\TicketsRequest as UpdateRequest;
use App\Mail\TicketCreated;
use App\Mail\TicketReply;


/**
 * Class TicketsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TicketsCrudController extends MasterController
{

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Tickets');
        $this->crud->setRoute('customer/tickets');
        $this->crud->setEntityNameStrings('tickets', __('customer_msg.menu_SupportTickets'));
        $this->crud->setEditView('vendor.custom.common.tickets.edit');
        $this->crud->setCreateView('vendor.custom.common.tickets.create');
        //$this->crud->removeButton('create');
		$this->crud->removeButton( 'delete' );

        $user = \Auth::guard('customer')->user();
        $company = \Request::query('company');
        $this->crud->query->where('parent_chat_id', 0)->where(function($query) use($user){
            return $query->where('receiver_id',$user->id)->orWhere('sender_id',$user->id);
        });
       // $this->crud->query->where('file_servcie_id', 0);
		// $this->crud->query->WhereNull('subject');
        $this->crud->query->orderBy('id', 'DESC');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

		$this->crud->addColumn([
			'label' => "#",
		    'name' => 'is_read',
			'type' => "model_function",
			'function_name' => 'getUnreadMessage',

		]);

        $this->crud->addColumn([
           'name' => 'company',
           'label' => __('customer_msg.tb_header_Company')
        ]);

        $this->crud->addColumn([
           'name' => 'file_service_name',
            'label' => __('customer_msg.tb_header_FileService')
        ]);

       $this->crud->addColumn([
            'name' => 'is_closed',
           'label' => __('customer_msg.tb_header_TicketStatus'),
            'type'=>'boolean',
            'options'=>[0=> 'Open', 1=> 'Closed']
        ]);

        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => __('customer_msg.tb_header_CreatedAt')
        ]);

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    /**
     * Create resource
     * @return $response
     */
    public function create(){
        $data['fileServices'] = $this->user->fileServices;
        $data['crud'] = $this->crud;
        return view($this->crud->getCreateView(), $data);
    }

    /**
     * Store resource
     * @param StoreRequest $request
     * @return $response
     */
    public function store(StoreRequest $request)
    {
        $request->request->add(['sender_id'=> $this->user->id]);
        $request->request->add(['receiver_id'=> $this->user->company->owner->id]);
        if($request->uploaded_file != null){
            $request->request->add(['document'=> $request->uploaded_file]);
        }
        $redirect_location = parent::storeCrud($request);
		try{
			\Mail::to($this->user->company->owner->email)->send(new TicketCreated($this->user,$request->all()['subject']));
		}catch(\Exception $e){
			\Alert::error('Error in SMTP: '.__('admin.opps'))->flash();
		}
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

        $messages = \App\Models\Tickets::where('parent_chat_id', $entry->id)->get();
        /*
        $update=\App\Models\Tickets::where('receiver_id',$this->user->id)->where(function($query) use($entry){
            return $query->where('parent_chat_id',$entry->id)->orWhere('parent_chat_id',0);
        })->update(['is_read'=>1]); */

		$update=\App\Models\Tickets::where('receiver_id',$this->user->id)->where(function($query) use($entry){
            return $query->where('parent_chat_id',$entry->id)->orWhere('id',$entry->id);
        })->update(['is_read'=>1]);

        $fileService = [];

        if($entry->file_servcie_id != 0){
            $fileService = \App\Models\FileService::where('id', $entry->file_servcie_id)->first();
        }
        $data['entry'] = $entry;
        $data['crud'] = $this->crud;
        $data['messages'] = $messages;
        $data['fileService'] = $fileService;
        $data['saveAction'] = $this->getSaveAction();
        $data['fields'] = $this->crud->getUpdateFields($id);
        $data['title'] = trans('backpack::crud.edit').' '.$this->crud->entity_name;
        $data['id'] = $id;
        return view($this->crud->getEditView(), $data);
    }


    /**
     * Update resource
     * @param UpdateRequest $request
     * @param \App\Models\Tickets $ticket
     * @return $response
     */
    public function update(UpdateRequest $request, \App\Models\Tickets $ticket)
    {

        try{
            if(!empty($request->message) || !empty($request->uploaded_file))
            {

                $tickets = new \App\Models\Tickets();
                $tickets->parent_chat_id = $ticket->id;
                $tickets->sender_id = $this->user->id;
                $tickets->receiver_id = $this->company->owner->id;
                $tickets->message = $request->message;
                $tickets->subject = $ticket->subject;
                if($request->uploaded_file != null){
                      $tickets->document = $request->uploaded_file;
                }
                $tickets->save();
				try{
					\Mail::to($this->user->company->owner->email)->send(new TicketReply($this->user,$ticket->subject));
				}catch(\Exception $e){
					\Alert::error('Error in SMTP: '.__('admin.opps'))->flash();
				}
                $ticket->is_closed = 0;
                $ticket->save();

            }
            //return redirect()->back();
			return redirect(url('customer/tickets/'.$ticket->id.'/edit'));
        }catch(\Exception $e){
            \Alert::error($e->getMessage())->flash();
            return redirect(url('customer/tickets'));
        }
    }

    /**
     * Upload file
     * @param \Illuminate\Http\Request $request
     * @return $response
     */
    public function uploadFile(\Illuminate\Http\Request $request){
        if($request->hasFile('document')){
            if($request->file('document')->isValid()){
                $file = $request->file('document');
                $ext = $file->getClientOriginalExtension();

				$fileNameExt = $file->getClientOriginalName();
				$fileNameOnly = explode('.',$fileNameExt)[0];

                if(!isset($ext)){
                    $filename = $fileNameOnly."-".time() . '.dat';
                }
                /* else if($ext == "cod"){
                    return response()->json(['status'=> FALSE,'msg'=>'This extension is not allowed.'], 404);
                } */else{
                    $filename = $fileNameOnly."-".time() . '.' . $file->getClientOriginalExtension();
                    if($file->move(public_path('uploads/tickets'), $filename)){
                        return response()->json(['status'=> TRUE, 'file'=>$filename], 200);
                    }else{
                        return response()->json(['status'=> FALSE], 404);
                    }
               }
            }else{
                return response()->json(['status'=> FALSE], 404);
            }
        }else{
            return response()->json(['status'=> FALSE], 404);
        }
    }

    /**
     * download orginal file
     * @param \App\Models\FileService $fileService
     * @return $response
     */
    public function downloadFile(\App\Models\Tickets $ticket){
        try{
            $file = public_path('uploads/tickets/' . $ticket->document);
            if(\File::exists($file)){
                $fileExt = \File::extension($file);
                $fileName = $ticket->id.'-document.'.$fileExt;
                return response()->download($file, $fileName);
            }else{
                \Alert::error(__('customer.opps'))->flash();
            }
        }catch(\Exception $e){
            \Alert::error(__('customer.opps'))->flash();
        }
        return redirect('customer/tickets');

    }

    /**
     * download orginal file
     * @param \App\Models\FileService $fileService
     * @return $response
     */
    public function markClose(\App\Models\Tickets $ticket){
        $tickets = $this->crud->getEntry($ticket->id);
        $tickets->is_closed = 1;
        if($tickets->save()){
            \Alert::success(__('Ticket has been closed succesfully.'))->flash();
            return redirect(url('customer/tickets'));
        }else{
            \Alert::error(__('customer.opps'))->flash();
            return redirect(url('customer/tickets'));
        }
    }

    /**
     * destroy ticket with all child thread
     * @param \App\Models\Tickets $ticket
     * @return $response
     */
    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $path = public_path()."/uploads/tickets/";
		$fileDeleteParent = \App\Models\Tickets::where('parent_chat_id',$id)->get()->toArray();


        $res = \App\Models\Tickets::where('parent_chat_id',$id)->delete();
		if(!empty($fileDeleteParent)) {
			foreach($fileDeleteParent as $val) {
				if($val['document']) {
					if(\File::exists($path."".$val['document'])){
						\File::delete($path."".$val['document']);
					}
				}
			}
		}

		$fileDelete = \App\Models\Tickets::find($id)->document;
		if($fileDelete) {
			if(\File::exists($path."".$fileDelete)){
				\File::delete($path."".$fileDelete);
			}
		}
        return $this->crud->delete($id);
    }
}
