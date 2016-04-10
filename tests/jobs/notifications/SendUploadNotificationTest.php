<?php

use App\Jobs\Notifications\SendUploadNotificationMail;
use App\Models\Process;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spinen\MailAssertions\MailTracking;

class SendUploadNotificationTest extends TestCase
{
    use MailTracking, DatabaseTransactions;

    /** @test */
    public function it_sends_email_to_user()
    {
        $process = factory(Process::class)->create(['email' => 'foo@bar.com']);
        $linkToUpload = route('process.show', [$process->id]);

        $mail = $this->app->make('Illuminate\Contracts\Mail\Mailer');

        $job = new SendUploadNotificationMail($process);
        $job->handle($mail);

        $this->seeEmailTo('foo@bar.com');
        $this->seeEmailSubjectEquals("We're ready to receive your User Agent data");
        $this->seeEmailContains($linkToUpload);
    }
}
