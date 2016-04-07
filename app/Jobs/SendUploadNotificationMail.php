<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\Process;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Mail\Mailer as Mail;

class SendUploadNotificationMail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $process;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Process $process)
    {
        $this->process = $process;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mail $mail)
    {
        $mail->send('emails.upload-notification', ['process' => $this->process], function($m){

            $m->to($this->process->email);
            $m->subject("We're ready to receive your User Agent data");

        });
    }
}
