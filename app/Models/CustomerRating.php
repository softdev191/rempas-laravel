<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRating extends Model
{
	protected $fillable = ['rating', 'user_id', 'company_id'];
}
