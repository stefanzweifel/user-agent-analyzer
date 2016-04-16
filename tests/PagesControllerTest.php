<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class PagesControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_shows_terms_of_service_page()
    {
        $this->visit('terms-of-service')
            ->see('Terms of Service');
    }
}
