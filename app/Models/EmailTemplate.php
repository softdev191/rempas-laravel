<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use CrudTrait;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'label', 'subject', 'body', 'is_active'
    ];

    /**
     * Get the company that owns email template.
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
    public function getEmailTypeAttribute($value) {
        return config('site.emailes')[$this->label];
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
