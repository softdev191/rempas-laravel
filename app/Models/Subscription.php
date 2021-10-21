<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Subscription extends Model
{
    use CrudTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'pay_agreement_id', 'description', 'start_date', 'status','is_trial','trial_days','is_immediate'
    ];


    /**
     * Get the user for the subscription.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the subscription Payments.
     */
    public function subscriptionPayments()
    {
        return $this->hasMany('App\Models\SubscriptionPayment');
    }


    /**
     * Get the next billing date.
     *
     * @param  string  $value
     * @return string
     */
    public function getNextBillingDateAttribute($value) {
        $spayment = $this->subscriptionPayments()->orderBy('id', 'DESC')->first();
        if($spayment){
            return \Carbon\Carbon::parse($spayment->next_billing_date)->format('d M Y g:i A');
        }

        return "";
    }


    /**
     * Get the start date.
     *
     * @param  string  $value
     * @return string
     */
    public function getStartDateAttribute($value) {
        return \Carbon\Carbon::parse($value)->format('d M Y g:i A');
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
