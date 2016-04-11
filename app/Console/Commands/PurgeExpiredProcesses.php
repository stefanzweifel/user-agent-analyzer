<?php

namespace App\Console\Commands;

use App\Jobs\PurgeExpiredProcess;
use App\Models\Process;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class PurgeExpiredProcesses extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'processes:purge-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge Expired Processes';

    protected $process;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Process $process)
    {
        parent::__construct();

        $this->process = $process;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->process->isExpiredScope()->isNotFinishedScope()->chunk(10, function($processes) {

            foreach ($processes as $process) {

                $this->dispatch(new PurgeExpiredProcess($process));

            }

        });
    }
}
