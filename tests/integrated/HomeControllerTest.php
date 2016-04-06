<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
        $this->expectsJobs(App\Jobs\SendUploadNotificationMail::class);

        $this->visit('/')
            ->type('foo@bar.com', 'email')
            ->press("Let's do this!")
            ->seePageIs('/');
    }
}
