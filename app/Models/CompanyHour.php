<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyHour extends Model
{
	protected $fillable = [
        'company_id',
        'mon_from',
        'mon_to',
        'tue_from',
        'tue_to',
        'wed_from',
        'wed_to',
        'thu_from',
        'tru_to',
        'fri_from',
        'fri_to',
        'sat_from',
        'sat_to',
        'sun_frrom',
        'sun_to',
        'notify_check',
    ];
}
