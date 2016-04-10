<?php

namespace App\Jobs;

use App\Models\Process;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StartParsingGroupedByUserAgent extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    use DispatchesJobs;

    protected $process;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Process $process)
    {
        $this->process = $process;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $uniqueUserAgents = $this->process->userAgents()->groupBy('ua_string')->get();

        foreach ($uniqueUserAgents as $userAgent) {
            $this->dispatch(new ParseUserAgent($userAgent));
        }
    }
}
