<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\Process;
use App\Models\UserAgent;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReadCsvFile extends Job implements ShouldQueue
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
        $this->process->update(['start_at' => Carbon::now()]);
        $files = $this->process->getMedia('csv-files');

        foreach ($files as $file) {

            \Excel::load($file->getPath(), function($reader) {

                foreach ($reader->get() as $row) {

                    $userAgent = UserAgent::create([
                        'ua_string' => $row->useragent,
                        'process_id' => $this->process->id
                    ]);

                    $this->dispatch(new \App\Jobs\ParseUserAgent($userAgent));
                }

            });

        }
    }
}
