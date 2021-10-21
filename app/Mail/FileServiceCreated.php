<?php

namespace App\Mail;

use App\Models\FileService;
use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FileServiceCreated extends Mailable
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
        $emailTemplate = EmailTemplate::where('company_id', $this->fileService->user->company->id)->where('label', 'new-file-service-created-email')->first(['subject', 'body']);
        if($emailTemplate){
            $subject = $emailTemplate->subject;
            $body = $emailTemplate->body;

            $body = str_replace('##APP_NAME', $this->fileService->user->company->name, $body);
            $body = str_replace('##APP_LOGO', asset('uploads/logo/'. $this->fileService->user->company->logo), $body);
            $body = str_replace('##LINK', $this->fileService->user->company->domain_link.'/admin/file-service', $body);
            $body = str_replace('##CUSTOMER_NAME', $this->fileService->user->full_name, $body);
            $body = str_replace('##CAR_NAME', $this->fileService->car, $body);

            $body = str_replace('##MAKE', $this->fileService->make, $body);
            $body = str_replace('##MODEL', $this->fileService->model, $body);
            $body = str_replace('##GENERATION', $this->fileService->generation, $body);
            $body = str_replace('##ECU', $this->fileService->ecu, $body);
            $body = str_replace('##ENGINE_HP', $this->fileService->engine_hp, $body);
            $body = str_replace('##ENGINE', $this->fileService->engine, $body);
            $body = str_replace('##YEAR', $this->fileService->year, $body);
            $body = str_replace('##GEARBOX', config('site.file_service_gearbox')[$this->fileService->gearbox], $body);
            $body = str_replace('##READING_TOOL', $this->fileService->reading_tool, $body);
            $body = str_replace('##LICENSE_PLATE', $this->fileService->license_plate, $body);
            $body = str_replace('##NOTE_TO_ENGINEER', $this->fileService->note_to_engineer, $body);
            $body = str_replace('##TUNING_TYPE_ID', $this->fileService->tuningType->label, $body);
            $body = str_replace('##TUNING_TYPE_OPTION', $this->fileService->tuningTypeOptions()->pluck('label')->implode(','), $body);
            $body = str_replace('##FUEL_TYPE', $this->fileService->fuel_type, $body);

            $this->subject($subject)
                ->view('emails.layout')
                ->with(['body'=>$body]);
        }
    }
}
