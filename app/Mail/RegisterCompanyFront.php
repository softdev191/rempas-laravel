<?php

namespace App\Mail;

use App\User;
use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterCompanyFront extends Mailable
{
    use Queueable, SerializesModels;

	/**
     * The user instance.
     *
     * @var User
     */

    public $user;
	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
       $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $emailTemplate = EmailTemplate::where('company_id', 1)->where('subject', 'Welcome: Company has been registered')->first(['subject', 'body']);

        if($emailTemplate){
            $subject = $emailTemplate->subject;
            $body = $emailTemplate->body;
			$body = str_replace('##USER_NAME', $this->user->first_name, $body);			

            $this->subject($subject)
                ->view('emails.layout')
                ->with(['body'=>$body]);

        }
    }
}
