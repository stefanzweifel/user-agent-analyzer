<?php

namespace App\Jobs;

use App\Jobs\Notifications\SendSuccessNotification;
use App\Models\Process;
use App\Models\Report;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateReport extends Job implements ShouldQueue
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
    public function handle(Report $report)
    {
        $reportData = $this->process->getReportData();

        $report->create([
            'process_id' => $this->process->id,
            'total'      => $reportData->sum('count'),
            'desktop'    => $reportData->where('device_type_id', 1)->first() ? $reportData->where('device_type_id', 1)->first()->count : 0,
            'tablet'     => $reportData->where('device_type_id', 2)->first() ? $reportData->where('device_type_id', 2)->first()->count : 0,
            'mobile'     => $reportData->where('device_type_id', 3)->first() ? $reportData->where('device_type_id', 3)->first()->count : 0,
            'robot'      => $reportData->where('device_type_id', 4)->first() ? $reportData->where('device_type_id', 4)->first()->count : 0,
            'other'      => 0,
            'unknown'     => $reportData->where('device_type_id', 5)->first() ? $reportData->where('device_type_id', 5)->first()->count : 0,
        ]);

        // Send Notification
        $this->dispatch(new SendSuccessNotification($this->process));
    }
}
