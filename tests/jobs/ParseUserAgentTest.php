<?php

use App\Jobs\CreateReport;
use App\Jobs\ParseUserAgent;
use App\Models\Process;
use App\Models\UserAgent;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Jenssegers\Agent\Agent;

class ParseUserAgentTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_uses_cached_user_agent()
    {
        $this->markTestIncomplete(
            'Implement this test!'
        );
    }

    /** @test */
    public function it_dispatches_next_job()
    {
        $this->expectsJobs(CreateReport::class);

        $process = factory(Process::class)->create();
        $userAgent = factory(UserAgent::class)->create([
            'process_id'     => $process->id,
            'device_type_id' => 0
        ]);
        $agent  = app()->make(Agent::class);

        $job = new ParseUserAgent($userAgent);
        $job->handle($agent);
    }

    /** @test */
    public function it_doesnt_dispatch_next_job_if_there_are_user_agents_left_to_parse()
    {
        $this->doesntExpectJobs(CreateReport::class);

        $process = factory(Process::class)->create();
        $userAgent = factory(UserAgent::class, 5)->create([
            'process_id' => $process->id,
            'device_type_id' => 0
        ]);
        $agent  = app()->make(Agent::class);

        $job = new ParseUserAgent($userAgent->first());
        $job->handle($agent);
    }

    /** @test */
    public function it_mass_updates_user_agent_models()
    {
        $process = factory(Process::class)->create();
        $userAgents = factory(UserAgent::class, 2)->create([
            'process_id' => $process->id,
            'device_type_id' => 0,
            'ua_string' => 'Mozilla/5.0 (iPad; U; CPU OS 4_3_3 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5'

        ]);
        $agent  = app()->make(Agent::class);

        $job = new ParseUserAgent($userAgents->first());
        $job->handle($agent);

        $this->seeInDatabase('user_agents', [
            'id' => $userAgents[0]->id,
            'device_type_id' => 2
        ]);
        $this->seeInDatabase('user_agents', [
            'id' => $userAgents[1]->id,
            'device_type_id' => 2
        ]);

    }

    /** @test */
    public function it_detects_tablets()
    {
        $process = factory(Process::class)->create();
        $userAgent = factory(UserAgent::class)->create([
            'process_id' => $process->id,
            'device_type_id' => 0,
            'ua_string' => 'Mozilla/5.0 (iPad; U; CPU OS 4_3_3 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5'
        ]);
        $agent  = app()->make(Agent::class);

        $job = new ParseUserAgent($userAgent);
        $job->handle($agent);

        $this->seeInDatabase('user_agents', [
            'id'             => $userAgent->id,
            'ua_string'      => $userAgent->ua_string,
            'device_type_id' => 2
        ]);
    }

    /** @test */
    public function it_detects_mobile_devices()
    {
        $process = factory(Process::class)->create();
        $userAgent = factory(UserAgent::class)->create([
            'process_id' => $process->id,
            'device_type_id' => 0,
            'ua_string' => 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_3_3 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5'
        ]);
        $agent  = app()->make(Agent::class);

        $job = new ParseUserAgent($userAgent);
        $job->handle($agent);

        $this->seeInDatabase('user_agents', [
            'id'             => $userAgent->id,
            'ua_string'      => $userAgent->ua_string,
            'device_type_id' => 3
        ]);
    }

    /** @test */
    public function it_detects_desktops()
    {
        $process = factory(Process::class)->create();
        $userAgent = factory(UserAgent::class)->create([
            'process_id' => $process->id,
            'device_type_id' => 0,
            'ua_string' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2700.0 Safari/537.36'
        ]);
        $agent  = app()->make(Agent::class);

        $job = new ParseUserAgent($userAgent);
        $job->handle($agent);

        $this->seeInDatabase('user_agents', [
            'id'             => $userAgent->id,
            'ua_string'      => $userAgent->ua_string,
            'device_type_id' => 1
        ]);
    }

    /** @test */
    public function it_detects_robots()
    {
        $process = factory(Process::class)->create();
        $userAgent = factory(UserAgent::class)->create([
            'process_id' => $process->id,
            'device_type_id' => 0,
            'ua_string' => 'Googlebot/2.1 (+http://www.googlebot.com/bot.html)'
        ]);
        $agent  = app()->make(Agent::class);

        $job = new ParseUserAgent($userAgent);
        $job->handle($agent);

        $this->seeInDatabase('user_agents', [
            'id'             => $userAgent->id,
            'ua_string'      => $userAgent->ua_string,
            'device_type_id' => 4
        ]);
    }

}
