<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Transaction extends Model
{
    use CrudTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'credits', 'type', 'description', 'status'
    ];


    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the one credit.
     *
     * @param  string  $value
     * @return string
     */
    public function getCreditsWithTypeAttribute($value) {

        return ($this->type == 'S')?'-'.number_format($this->credits, 2):number_format($this->credits, 2);
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
