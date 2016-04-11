<?php

use App\Jobs\PurgeExpiredProcess;
use App\Models\Process;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PurgeExpiredProcessTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_purges_expired_processes()
    {
        $process = factory(Process::class)->create(['expires_at' => Carbon::parse('yesterday')]);

        $job = new PurgeExpiredProcess($process);
        $job->handle();

        $processInDatabase = Process::whereId($process->id)->withTrashed()->first();

        $this->assertTrue($processInDatabase->trashed());
    }

    /** @test */
    public function it_doesnt_purge_not_expired_processes()
    {
        $process = factory(Process::class)->create(['expires_at' => Carbon::parse('tomorrow')]);

        $job = new PurgeExpiredProcess($process);
        $job->handle();

        $processInDatabase = Process::whereId($process->id)->withTrashed()->first();

        $this->assertFalse($processInDatabase->trashed());
    }

    /** @test */
    public function it_doesnt_purge_finished_processes()
    {
        $process = factory(Process::class)->create([
            'expires_at'  => Carbon::parse('2 days ago'),
            'start_at'    => Carbon::parse('yesterday'),
            'finished_at' => Carbon::parse('yesterday'),
        ]);

        $job = new PurgeExpiredProcess($process);
        $job->handle();

        $processInDatabase = Process::whereId($process->id)->withTrashed()->first();

        $this->assertFalse($processInDatabase->trashed());
    }
}
