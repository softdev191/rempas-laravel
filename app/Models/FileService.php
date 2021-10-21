<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class FileService extends Model
{
    use CrudTrait;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'tuning_type_id', 'make', 'model', 'generation', 'engine', 'ecu', 'engine_hp', 'year', 'gearbox','fuel_type', 'reading_tool', 'license_plate', 'vin', 'orginal_file', 'modified_file', 'note_to_engineer', 'notes_by_engineer', 'status', 'displayable_id'
    ];


    /**
     * Get the user that owns the file service.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the tuningType that owns the file service.
     */
    public function tuningType()
    {
        return $this->belongsTo('App\Models\TuningType');
    }

    /**
     * The tuning type options that belong to the file service.
     */
    public function tuningTypeOptions()
    {
        return $this->belongsToMany('App\Models\TuningTypeOption');
    }

    /**
     * The tuning type options that belong to the file service.
     */
    public function Tickets()
    {
        return $this->hasOne('App\Models\Tickets','file_servcie_id');
    }

    /**
     * Get the status attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getStatusAttribute($value) {
        return config('site.file_service_staus')[$value];
    }

    /**
     * Get the car attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getCarAttribute($value) {
        return $this->make.' '.$this->model.' '.$this->generation;
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
