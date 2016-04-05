<?php

use App\Models\Process;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Spinen\MailAssertions\MailTracking;

class SendUploadNotificationTest extends TestCase
{
    use MailTracking;
    use DatabaseMigrations;

    /** @test */
    public function it_sends_email_to_user()
    {
        $process = factory(Process::class)->create(['email' => 'foo@bar.com']);
        $linkToUpload = route('process.upload', [$process->id]);

        $mail = $this->app->make('Illuminate\Contracts\Mail\Mailer');

        $job = new \App\Jobs\SendUploadNotificationMail($process);
        $job->handle($mail);

        $this->seeEmailTo('foo@bar.com');
        $this->seeEmailContains($linkToUpload);
    }
}
