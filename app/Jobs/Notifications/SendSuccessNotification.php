<?php

namespace App\Jobs\Notifications;

use App\Jobs\Job;
use Illuminate\Contracts\Mail\Mailer as Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Process;

class SendSuccessNotification extends Job implements ShouldQueue
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
        $mail->send('emails.success-notification', ['process' => $this->process], function ($m) {

            $m->to($this->process->email);
            $m->subject("You're files are processed. Here are you're results.");

        });
    }
}
