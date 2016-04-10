<?php

use App\Jobs\ReadCsvFile;
use App\Jobs\StartParsingGroupedByUserAgent;
use App\Models\Process;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Maatwebsite\Excel\Excel;

class ReadCsvFileTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_start_at_date_of_given_process()
    {
        $pathToTestFile = base_path('tests/support/ua-test.csv');
        $process = factory(Process::class)->create();
        $process->addMedia($pathToTestFile)->preservingOriginal()->toCollection('csv-files');
        $carbon = app()->make(Carbon::class);
        $excel = app()->make(Excel::class);

        $job = new ReadCsvFile($process);
        $job->handle($carbon, $excel);

        $this->assertTrue(
            Process::whereId($process->id)->where('start_at', '>=', $carbon->now())->exists()
        );
    }

    /** @test */
    public function it_stores_user_agent_in_database()
    {
        $pathToTestFile = base_path('tests/support/ua-test.csv');
        $process = factory(Process::class)->create();
        $process->addMedia($pathToTestFile)->preservingOriginal()->toCollection('csv-files');
        $carbon = app()->make(Carbon::class);
        $excel = app()->make(Excel::class);

        $job = new ReadCsvFile($process);
        $job->handle($carbon, $excel);

        $this->seeInDatabase('user_agents', [
            'process_id' => $process->id,
            'ua_string'  => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:32.0) Gecko/20100101 Firefox/32.0',
        ]);
    }

    /** @test */
    public function it_dispatches_new_job()
    {
        $this->expectsJobs(StartParsingGroupedByUserAgent::class);

        $process = factory(Process::class)->create();
        $carbon = app()->make(Carbon::class);
        $excel = app()->make(Excel::class);

        $job = new ReadCsvFile($process);
        $job->handle($carbon, $excel);
    }
}
