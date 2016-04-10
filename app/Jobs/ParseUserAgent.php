<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Jobs\Notifications\SendSuccessNotification;
use App\Models\UserAgent;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Jenssegers\Agent\Agent;

class ParseUserAgent extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    protected $userAgent;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(UserAgent $userAgent)
    {
        $this->userAgent = $userAgent;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Agent $agent)
    {
        // Cache this query forever. Because User Agents should never change
        $isAlreadyParsed = UserAgent::where('ua_string', $this->userAgent->ua_string)->processed()->first();

        if ($isAlreadyParsed) {
            $parsedDeviceTypeId = $isAlreadyParsed->device_type_id;
        }
        else {

            $agent->setUserAgent($this->userAgent->ua_string);

            if ($agent->isTablet() && $agent->isMobile()) {
                $parsedDeviceTypeId = 2;
            }
            else if ($agent->isMobile()) {
                $parsedDeviceTypeId = 3;
            }
            else if ($agent->isRobot()) {
                $parsedDeviceTypeId = 4;
            }
            else if (!$agent->isTablet() && !$agent->isMobile()) {
                $parsedDeviceTypeId = 1;
            }
            else {
                $parsedDeviceTypeId = 5;
            }
        }

        // Mass Update Models
        $userAgentsToUpdate = $this->userAgent->process->userAgents()->notProcessed()->where('ua_string', $this->userAgent->ua_string)->get();

        foreach ($userAgentsToUpdate as $userAgentToUpdate) {
            $userAgentToUpdate->update(['device_type_id' => $parsedDeviceTypeId]);
        }


        // Are there any UserAgents left? If no, send SuccessNotification
        $notProcessed = $this->userAgent->process->userAgents()->notProcessed()->count();

        if ($notProcessed == 0) {
            $this->userAgent->process->update(['finished_at' => Carbon::now()]);

            $this->dispatch(new CreateReport($this->userAgent->process));
        }
    }
}
