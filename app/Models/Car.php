<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'brand', 'model', 'year', 'engine_type', 'std_bhp', 'tuned_bhp', 'std_torque', 'tuned_torque', 'title'
    ];
}
