<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use CrudTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'transaction_id', 'invoice_id', 'vat_number', 'vat_percentage', 'tax_amount', 'amount', 'description', 'status', 'displayable_id'
    ];


    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }


    /**
     * Get the customer attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getCustomerAttribute() {
        return @$this->user->full_name;
    }
	
	/**
     * Get the customer company attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getCustomerCompanyAttribute() {
        return @$this->user->business_name;
    }

    /**
     * Get the amount with sign.
     *
     * @param  string  $value
     * @return string
     */
    public function getAmountWithSignAttribute() {

        return config('site.currency_sign').' '.number_format($this->amount, 2);
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
