<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class TuningType extends Model
{
    use CrudTrait;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'label', 'credits', 'order_as'
    ];

    /**
     * Get the company that owns tuning type.
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    /**
     * Get the fileService for owns tuning type.
     */
    public function fileServices()
    {
        return $this->hasMany('App\Models\FileService');
    }

    /**
     * Get the tuning options for the tuning type.
     */
    public function tuningTypeOptions()
    {
        return $this->hasMany('App\Models\TuningTypeOption');
    }

    /**
     * Get tuning options with link.
     *
     * @return string
     */
    public function getTuningOptionsWithLink() {

        $url = backpack_url('tuning-type/'.$this->id.'/options');
        return '<a href="'.url($url).'">'.$this->tuningTypeOptions()->count().' tuning options</a>';
    }

    /**
     * Get the credits.
     *
     * @param  string  $value
     * @return string
     */
    public function getCreditsAttribute($value) {
        return $value;
    }
	
	/**
     * Get the  formated credits.
     *
     * @param  string  $value
     * @return string
     */
    public function getFormatedCreditsAttribute() {
		return number_format($this->credits, 2);
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

    /**
     * Get the updated at.
     *
     * @param  string  $value
     * @return string
     */
    public function getUpdatedAtAttribute($value) {
        return \Carbon\Carbon::parse($value)->format('d M Y g:i A');
    }

}
