<?php

namespace App\Models;

use App\Models\UserAgent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class Process extends UuidModel implements HasMedia
{
    use HasMediaTrait;

    protected $fillable = ['email', 'finished_at', 'expires_at', 'start_at', 'finished_at'];

    protected $dates = ['expires_at', 'finished_at', 'start_at'];

    /**
     * Retrieve the Email attribute.
     *
     * @param   mixed
     * @return  string
     */
    public function getEmailAttribute($value)
    {
        return Crypt::decrypt($value);
    }

    /**
     * Set the email attribute.
     *
     * @param   mixed
     * @return  void
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = Crypt::encrypt($value);
    }

    /**
     * Relationship with the UserAgent model.
     *
     * @return    Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userAgents()
    {
        return $this->hasMany(UserAgent::class);
    }

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    public function isFinished()
    {
        return !is_null($this->finished_at) && $this->finished_at->isPast();
    }

    public function hasReceivedFile()
    {
        return (count($this->getMedia('csv-files')) > 0);
    }

    public function isProcessing()
    {
        return !is_null($this->start_at) && $this->start_at->isPast() && !$this->isFinished();
    }

    public function getReport()
    {
        return $this->userAgents()
            ->select([
                \DB::raw("count('id') as count"),
                'device_type_id'
            ])
            ->groupBy('device_type_id')->get();
    }

}
