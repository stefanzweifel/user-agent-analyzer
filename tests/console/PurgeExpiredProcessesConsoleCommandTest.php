<?php

use App\Console\Commands\PurgeExpiredProcesses;
use App\Jobs\PurgeExpiredProcess;
use App\Models\Process;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class PurgeExpiredProcessesConsoleCommandTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_dispatches_correct_job()
    {
        $this->expectsJobs(PurgeExpiredProcess::class);

        $process = factory(Process::class)->create(['expires_at' => Carbon::parse('yesterday')]);

        \Artisan::call('process:purge-expired');
    }

}
