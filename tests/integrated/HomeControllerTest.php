<?php

use App\Jobs\Notifications\SendUploadNotificationMail;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HomeControllerTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_shows_sign_up_form()
    {
        $this->visit('/')
            ->see('Email');
    }

    /** @test */
    public function it_throws_error_if_an_invalid_mail_is_passed()
    {
        $this->visit('/')
            ->type('not-an-email', 'email')
            ->press("Let's do this!")
            ->seePageIs('/');
    }

    /** @test */
    public function it_redirects_if_valid_email_is_passed()
    {
        $this->expectsJobs(SendUploadNotificationMail::class);

        $this->visit('/')
            ->type('foo@bar.com', 'email')
            ->press("Let's do this!")
            ->seePageIs('/')
            ->see('We sent an email with an upload link to you.');
    }

    /** @test */
    public function it_shows_404_page()
    {
        $response = $this->call('GET', '/this-route-does-not-exist');

        $this->assertEquals(404, $response->getStatusCode());
    }
}
