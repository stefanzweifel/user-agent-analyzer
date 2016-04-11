<?php

namespace App\Jobs;

use App\Models\Process;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PurgeExpiredProcess extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

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
        if ($this->process->isExpired() && ! $this->process->isFinished()) {
            $this->process->delete();
        }
    }
}
