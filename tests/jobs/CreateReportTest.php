<?php

use App\Jobs\CreateReport;
use App\Jobs\Notifications\SendSuccessNotification;
use App\Models\Process;
use App\Models\Report;
use App\Models\UserAgent;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateReportTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_new_report_for_parsed_user_agents()
    {
        $process = factory(Process::class)->create();
        $userAgents = factory(UserAgent::class, 100)->create(['process_id' => $process->id]);
        $report = new Report();

        $job = new CreateReport($process);
        $job->handle($report);

        $this->seeInDatabase('reports', [
            'process_id' => $process->id,
            'other'      => 0,
            'total'      => 100,
        ]);
    }

    /** @test */
    public function it_dispatches_sucess_notification_mail()
    {
        $this->expectsJobs(SendSuccessNotification::class);

        $process = factory(Process::class)->create();
        $userAgents = factory(UserAgent::class, 1)->create(['process_id' => $process->id]);
        $report = new Report();

        $job = new CreateReport($process);
        $job->handle($report);
    }
}
