<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TuningCreditTire extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'amount', 'group_type'
    ];


    /**
     * Get the tuning credit groups for the credit tire.
     */
    public function tuningCreditGroups()
    {
        return $this->belongsToMany('App\Models\TuningCreditGroup', 'tuning_credit_group_tuning_credit_tire', 'tuning_credit_tire_id', 'tuning_credit_group_id');
    }

    /**
     * Get the company that owns tuning credit tire.
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
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
