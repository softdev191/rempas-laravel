<?php

namespace App\Mail;

use App\Models\FileService;
use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FileServiceLimited extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The file service instance.
     *
     * @var FileService
     */

    public $fileService;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $emailTemplate = EmailTemplate::where('company_id', $this->fileService->user->company->id)
          ->where('label', 'file-service-upload-limited')
          ->first(['subject', 'body']);
        if($emailTemplate){
            $subject = $emailTemplate->subject;
            $body = $emailTemplate->body;

            $body = str_replace('##APP_NAME', $this->fileService->user->company->name, $body);
            $body = str_replace('##USER_NAME', $this->fileService->user->full_name, $body);
            $body = str_replace('##COMPANY_NAME', $this->fileService->user->company->name, $body);

            $this->subject($subject)
                ->view('emails.layout')
                ->with(['body'=>$body]);
        }
    }
}
