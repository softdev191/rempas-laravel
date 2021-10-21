<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Tickets extends Model
{
    use CrudTrait;

    /**
     * The table.
     *
     * @var array
     */
    protected $table = 'tickets';

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $fillable = ['sender_id', 'receiver_id', 'file_servcie_id', 'parent_chat_id', 'subject', 'message', 'document', 'is_closed','is_read'];
    
    /**
     * Get the user that owns the company.
    */
    public function sender()
    {
        return $this->belongsTo('App\User', 'sender_id');
    }
    
    /**
     * Get the user that owns the company.
    */
    public function receiver()
    {
        return $this->belongsTo('App\User', 'receiver_id');
    }
    
    /**
     * Get the user that owns the company.
    */
    public function FileService()
    {
        return $this->belongsTo('App\Models\FileService', 'file_servcie_id');
    }

    /**
     * Return the child messages array for this model.
     *
     * @return array
     */
    public function childrens() {
        return $this->hasMany('App\Models\Tickets', 'parent_chat_id', 'id');
    }

    /**
     * Get the company attribute.
    */
    public function getLastMessageAttribute() {
        if($this->childrens()->count() > 0){
            return @$this->childrens()->orderBy('id', 'Desc')->first()->message;
        }else{
            return $this->message;
        }
    }
	
	/**
	* Get Read/Unread Status
	*/
	
	public function getUnreadMessage() { 
		if($this->childrens()->count() == 0) {
			$status = @$this->is_read;
			$receiverID = @$this->receiver_id;
		}
		else {
			$status = @$this->childrens()->orderBy('id', 'Desc')->first()->is_read;
			$receiverID = @$this->childrens()->orderBy('id', 'Desc')->first()->receiver_id;
		}
		
		$id = \Auth::user()->id;
		if($receiverID) {	
			if($status == '0' && $receiverID == $id) { 
				return '<strong class="envelope"><i class="fa fa-envelope"></i></strong>';
			}
			else {
				return '<strong class="envelope-open"><i class="fa fa-envelope-open"></i></strong>';
			}
		}
		else {
			return '<strong class="envelope-open"><i class="fa fa-envelope-open"></i></strong>';
		}
    }
	

    /**
     * Get the company attribute.
    */
    public function getCompanyAttribute() {
        return @$this->sender->company->name;
    }
	
	/**
     * Get the car attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getCarAttribute($value) {
		return $this->FileService->make.' '.$this->FileService->model.' '.$this->FileService->generation;
    }
    
	/**
     * Get the File Service Name attribute.
    */
  	 public function getFileServiceNameAttribute() {
		if($this->FileService) {
			return $this->FileService->displayable_id;
		}else{
			//changes
				return 'General Enquiry';
		}
	}
	
	/**
     * Get the Customer's Name attribute.
    */
  	 public function getClientAttribute() {
		if($this->FileService != null) {
			return $this->FileService->user->first_name.' '.$this->FileService->user->last_name;		
		}else{
			//changes
				$receiverID = @$this->receiver_id;
				return $this->sender->first_name.' '.$this->sender->last_name;
		}		
	}
    
    /**
     * Get the created at.
     *
     * @param  string  $value
     * @return string
     */
    public function getCreatedAtAttribute($value) {
        return \Carbon\Carbon::parse($value)->format('d M Y g:i A');
    }
}
