<?php

namespace App;

use Backpack\CRUD\CrudTrait;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, CrudTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tuning_credit_group_id',
        'tuning_evc_credit_group_id',
        'company_id',
        'lang',
        'title',
        'first_name',
        'last_name',
        'business_name',
        'address_line_1',
        'address_line_2',
        'phone',
        'county',
        'town',
        'post_code',
        'email',
        'password',
        'tools',
        'state',
        'is_master',
        'is_admin',
        'is_staff',
        'is_active',
        'tuning_credits',
        'last_login',
        'more_info',
        'reseller_id',
        'private',
        'vat_number',
        'add_tax'
    ];

    /**
     * Scope a query to only include customer users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCustomer($query)
    {
        return $query->where('is_admin', 0);
    }

    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    /**
     * Get the company that owns the user.
    */
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    /**
     * Get the file services for the user.
     */
    public function tuningCreditGroup()
    {
        return $this->belongsTo('App\Models\TuningCreditGroup');
    }

    public function tuningEVCCreditGroup()
    {
        return $this->belongsTo('App\Models\TuningCreditGroup', 'tuning_evc_credit_group_id', 'id');
    }

    /**
     * Get the subscriptions for the user.
     */
    public function subscriptions()
    {
        return $this->hasMany('App\Models\Subscription');
    }

    /**
     * Get the transactions for the user.
     */
    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction');
    }

    /**
     * Get the file services for the user.
     */
    public function fileServices()
    {
        return $this->hasMany('App\Models\FileService');
    }

	/**
     * Get the tickets for the user.
     */
    public function tickets()
    {
        return $this->hasMany('App\Models\Tickets', 'receiver_id');
    }


    /**
     * Get the tickets count.
     *
     * @param  string  $value
     * @return string
     */
    public function getUnreadTicketsAttribute() {
		return $this->tickets()->where('is_read', 0)->count();
    }


    /**
     * Get the fullname.
     *
     * @param  string  $value
     * @return string
     */
    public function getFullNameAttribute() {

        return ucwords($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get the file service count.
     *
     * @param  string  $value
     * @return string
     */
    public function getFileServicesCountAttribute() {
        return $this->fileServices()->count();
    }


    /**
     * Get the tuning price group.
     *
     * @param  string  $value
     * @return string
     */
    public function getTuningPriceGroupAttribute() {
        return @$this->tuningCreditGroup->name;
    }

    public function getTuningEVCPriceGroupAttribute() {
        return @$this->tuningEVCCreditGroup->name;
    }

    public function getUserEvcTuningCreditsAttribute() {
        if ($this->reseller_id) {
            $url = "https://evc.de/services/api_resellercredits.asp";
            $dataArray = array(
                'apiid'=>'j34sbc93hb90',
                'username'=> $this->company->reseller_id,
                'password'=> $this->company->reseller_password,
                'verb'=>'getcustomeraccount',
                'customer' => $this->reseller_id
            );
            $ch = curl_init();
            $params = http_build_query($dataArray);
            $getUrl = $url."?".$params;
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $getUrl);
            curl_setopt($ch, CURLOPT_TIMEOUT, 500);

            $response = curl_exec($ch);
            if (strpos($response, 'ok') !== FALSE) {
                return str_replace('ok: ', '', $response);
            }
        }
        else {
            return '';
        }
    }

    /**
     * Get the tuning credits.
     *
     * @param  string  $value
     * @return string
     */
    public function getUserTuningCreditsAttribute($value) {
        return number_format($this->tuning_credits, 2);
    }

    /**
     * Get the last login.
     *
     * @param  string  $value
     * @return string
     */
    public function getLastLoginAttribute($value) {
        if(empty($value)){
            return 'Never';
        }
        return \Carbon\Carbon::parse($value)->diffForHumans();
    }

    /**
     * Get the Subscription Ended At.
     *
     * @param  string  $value
     * @return string
     */
    public function getSubscriptionEndedStringAttribute() {

        $string = "";
        $subscription = $this->subscriptions()->orderBy('id', 'DESC')->first();

        if($subscription){

                if($subscription->is_trial == 1){
                    $trailStartDate = \Carbon\Carbon::parse($subscription->start_date);
                    $updateDate = $trailStartDate->addDays($subscription->trial_days);
                    if($updateDate->isToday()){
                        $string = "Your plan is expiring today.";
                    }elseif($updateDate->isPast()){
                        $string = "Your plan has been expired. Please <a href='".url('admin/subscription/packages')."'><strong>click to subscribe</strong></a> any plan for uninterrupted services.";
                    }else{
                        $string = "Your trial plan will end on ".$updateDate->format('Y-m-d').".";
                    }
                }
                else {
                    if($subscription->status == 'Suspended' || $subscription->status == 'Cancelled'){
                        if($subscription->is_immediate==1){
                            $string = "Your plan has been expired. Please <a href='".url('admin/subscription/packages')."'><strong>click to subscribe</strong></a> any plan for uninterrupted services.";
                        }else{
                            $subscriptionPayment = $subscription->subscriptionPayments()->orderBy('id', 'DESC')->first();
                            if(isset($subscriptionPayment) && isset($subscriptionPayment->next_billing_date)){
                                if(\Carbon\Carbon::parse($subscriptionPayment->next_billing_date)->isToday()){
                                    $string = "Your plan is expiring today.";
                                }elseif(\Carbon\Carbon::parse($subscriptionPayment->next_billing_date)->isPast()){
                                    $string = "Your plan has been expired. Please <a href='".url('admin/subscription/packages')."'><strong>click to subscribe</strong></a> any plan for uninterrupted services.";
                                }else{
                                    $string = "Your plan will end on ".\Carbon\Carbon::parse($subscriptionPayment->next_billing_date)->format('Y-m-d').".";
                                }
                            }
                        }
                }
            }
        }else{
            if(!$this->is_master){
                $string = "You haven't subscribe any plan. Please <a href='".url('admin/subscription/packages')."'><strong>click to subscribe</strong></a> any plan for uninterrupted services.";
            }
        }

        return $string;
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

    /**
     * Set the reset password notification for user.
     */
    public function sendPasswordResetNotification($token) {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Check that user has active subscription.
     */
    public function hasActiveSubscription(){
        $subscription = $this->subscriptions()->orderBy('id', 'DESC')->first();
        if($subscription){

            if($subscription->is_trial == 1){
                $trailStartDate = \Carbon\Carbon::parse($subscription->start_date);
                if($trailStartDate->addDays($subscription->trial_days)->isPast()){
                    return FALSE;
                }
				else if(strtolower($subscription->status) == strtolower('Cancelled')) {
					return FALSE;
				}
                 else
                {
                     return TRUE;
                }
            }else{
                if(strtolower($subscription->status) == strtolower('Active')){
                    return TRUE;
                }else if(strtolower($subscription->status) == strtolower('Suspended') || strtolower($subscription->status) == strtolower('Cancelled')){
                    if($subscription->is_immediate==1){
                        return FALSE;
                    }else{
                        $subscriptionPayment = $subscription->subscriptionPayments()->orderBy('id', 'DESC')->first();
                        if(isset($subscriptionPayment) && isset($subscriptionPayment->next_billing_date)){
                            if(!\Carbon\Carbon::parse($subscriptionPayment->next_billing_date)->isPast()){
                                return FALSE;
                            }
                        }else{
                            return FALSE;
                        }
                    }
                }else{
                    return FALSE;
                }
            }
        }
        return FALSE;
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
