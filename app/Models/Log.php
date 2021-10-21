<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'table', 'staff_id', 'record_id',
    ];
}
