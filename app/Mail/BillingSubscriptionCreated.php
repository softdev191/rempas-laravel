<?php

namespace App\Mail;

use App\Models\Subscription;
use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BillingSubscriptionCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The subscription.
     *
     * @var Token
     */

    public $subscription;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $emailTemplate = EmailTemplate::whereLabel('new-subscription-email')->first(['subject', 'body']);

        if($emailTemplate){
            $masterCompany = \App\Models\Company::where('is_default', 1)->first(['name', 'logo']);

            $subject = $emailTemplate->subject;
            $subject = str_replace('##COMPANY_NAME', $this->subscription->user->company->name, $subject);

            $body = $emailTemplate->body;

            $body = str_replace('##APP_NAME', $masterCompany->name, $body);
            $body = str_replace('##APP_LOGO', asset('uploads/logo/'. $masterCompany->logo), $body);
            $body = str_replace('##COMPANY_NAME', $this->subscription->user->company->name, $body);
            $body = str_replace('##PLAN_ID', $this->subscription->pay_agreement_id, $body);
            $body = str_replace('##AGREEMENT_START_DATE', $this->subscription->created_at, $body);

            $this->subject($subject)
                ->view('emails.layout')
                ->with(['body'=>$body]);
        }
    }
}
