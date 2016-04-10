<?php

use App\Jobs\ParseUserAgent;
use App\Jobs\StartParsingGroupedByUserAgent;
use App\Models\Process;
use App\Models\UserAgent;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class StartParsingGroupedByUserAgentTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_dispatches_next_job()
    {
        $this->expectsJobs(ParseUserAgent::class);

        $process = factory(Process::class)->create();
        $userAgent = factory(UserAgent::class)->create([
            'process_id' => $process->id,
            'device_type_id' => 0
        ]);

        $job = new StartParsingGroupedByUserAgent($process);
        $job->handle();
    }

    /** @test */
    public function it_doesnt_dispatch_next_job_if_now_user_agents_are_available()
    {
        $this->doesntExpectJobs(ParseUserAgent::class);

        $process = factory(Process::class)->create();

        $job = new StartParsingGroupedByUserAgent($process);
        $job->handle();
    }

}
