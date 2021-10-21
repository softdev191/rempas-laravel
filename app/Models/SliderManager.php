<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class SliderManager extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'slider_managers';
    // protected $primaryKey = 'id';
	protected $primaryKey = 'id';
    // public $timestamps = false;
	public $timestamps = true;
    // protected $guarded = ['id'];
    protected $fillable = ['title', 'description', 'image', 'button_text', 'button_link'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

	
	public function showImage() {
		if($this->image) {
			return "<img src='/uploads/logo/$this->image' width='100' height='100'>";
		}
		else {
			return;
		}
	}	
	
	
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
