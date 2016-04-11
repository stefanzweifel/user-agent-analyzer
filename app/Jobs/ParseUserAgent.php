<?php

namespace App\Jobs;

use App\Models\UserAgent;
use Cache;
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
        $isAlreadyParsed = $this->getCachedUserAgent();

        if ($isAlreadyParsed) {
            $parsedDeviceTypeId = $isAlreadyParsed->device_type_id;
        } else {
            $agent->setUserAgent($this->userAgent->ua_string);

            if ($agent->isTablet() && $agent->isMobile()) {
                $parsedDeviceTypeId = 2;
            } elseif ($agent->isMobile()) {
                $parsedDeviceTypeId = 3;
            } elseif ($agent->isRobot()) {
                $parsedDeviceTypeId = 4;
            } elseif (!$agent->isTablet() && !$agent->isMobile()) {
                $parsedDeviceTypeId = 1;
            } else {
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

    /**
     * Search for processed UserAgent in Cache. Returns Model if found.
     *
     * @return UserAgent | null
     */
    public function getCachedUserAgent()
    {
        return Cache::rememberForever(base64_decode($this->userAgent->ua_string), function () {
            return UserAgent::where('ua_string', $this->userAgent->ua_string)->processed()->first();
        });
    }
}
