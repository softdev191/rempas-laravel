<?php

namespace App\Mail;

use App\User;
use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewCompanyApply extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The user instance.
     *
     * @var User
     */

    public $user;

    /**
     * The token.
     *
     * @var Token
     */

    public $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user,$maincompany)
    {
		
		$this->user = $user;
		$this->maincompany = $maincompany;
      //  $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
		
        $emailTemplate = EmailTemplate::where('company_id', $this->maincompany['id'])->where('label', 'new-company-apply')->first(['subject', 'body']);
		
        if($emailTemplate){
            $subject = $emailTemplate->subject;
            $body = $emailTemplate->body;
            $body = str_replace('##COMPANY_NAME##', $this->maincompany['name'], $body);
			$body = str_replace('##USER_NAME##', $this->user->first_name.' '.$this->user->last_name, $body);
			$body = str_replace('##APP_NAME##', config('app.name'), $body);
            $this->subject($subject)
                ->view('emails.layout')
                ->with(['body'=>$body]);

        }
    }
}
