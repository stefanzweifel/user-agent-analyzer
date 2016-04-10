<?php

use App\Jobs\Notifications\SendSuccessNotification;
use App\Models\Process;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Spinen\MailAssertions\MailTracking;

class SendSuccessNotificationTest extends TestCase
{
    use MailTracking, DatabaseTransactions;

    /** @test */
    public function it_sends_email_to_user()
    {
        $process       = factory(Process::class)->create(['email' => 'foo@bar.com']);
        $linkToProcess = route('process.show', [$process->id]);

        $mail = $this->app->make('Illuminate\Contracts\Mail\Mailer');

        $job = new SendSuccessNotification($process);
        $job->handle($mail);

        $this->seeEmailTo('foo@bar.com');
        $this->seeEmailSubjectEquals("You're files are processed. Here are you're results.");
        $this->seeEmailContains($linkToProcess);
    }
}
