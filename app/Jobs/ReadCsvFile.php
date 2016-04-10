<?php

namespace App\Jobs;

use App\Models\Process;
use App\Models\UserAgent;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Excel;

class ReadCsvFile extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    use DispatchesJobs;

    protected $process;

    protected $excel;

    protected $userAgent;

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
     *x.
     *
     * @return void
     */
    public function handle(Carbon $carbon, Excel $excel)
    {
        $this->excel = $excel;

        $this->process->update(['start_at' => $carbon->now()]);
        $files = $this->process->getMedia('csv-files');

        foreach ($files as $file) {
            $this->readFile($file);
        }

        $this->dispatch(new StartParsingGroupedByUserAgent($this->process));
    }

    /**
     * Read CSV File and create UserAgent Records.
     *
     * @param File $file
     *
     * @return void
     */
    public function readFile($file)
    {
        $this->excel->load($file->getPath(), function ($reader) {
            foreach ($reader->get() as $row) {
                $userAgent = UserAgent::create([
                    'ua_string'  => $row->useragent,
                    'process_id' => $this->process->id,
                ]);
            }
        });
    }
}
