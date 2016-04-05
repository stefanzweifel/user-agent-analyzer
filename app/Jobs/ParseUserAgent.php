<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\UserAgent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Jenssegers\Agent\Agent;

class ParseUserAgent extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

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
        $agent = $agent;
        $agent->setUserAgent($this->userAgent->ua_string);

        if ($agent->isMobile()) {
            $this->userAgent->update(['device_type_id' => 3]);
        }
        else if ($agent->isTablet()) {
            $this->userAgent->update(['device_type_id' => 2]);
        }
        else if (!$agent->isTablet() && !$agent->isMobile()) {
            $this->userAgent->update(['device_type_id' => 1]);
        }
        else {
            $this->userAgent->update(['device_type_id' => 4]);
        }


        // TODO: If this was the last user agent, mark process as finished
        // $this->process->update(['finished_at' => Carbon::now()]);
    }
}
