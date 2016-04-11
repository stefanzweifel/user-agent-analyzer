<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class Process extends UuidModel implements HasMedia
{
    use HasMediaTrait, SoftDeletes;

    protected $fillable = ['email', 'finished_at', 'expires_at', 'start_at', 'finished_at'];

    protected $dates = ['expires_at', 'finished_at', 'start_at'];

    /**
     * Retrieve the Email attribute.
     *
     * @param   mixed
     *
     * @return string
     */
    public function getEmailAttribute($value)
    {
        return Crypt::decrypt($value);
    }

    /**
     * Set the email attribute.
     *
     * @param   mixed
     *
     * @return void
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = Crypt::encrypt($value);
    }

    /**
     * Relationship with the UserAgent model.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
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
        return count($this->getMedia('csv-files')) > 0;
    }

    public function isProcessing()
    {
        return !is_null($this->start_at) && $this->start_at->isPast() && !$this->isFinished();
    }

    public function processingDuration($diffType = 'InSeconds')
    {
        $methodName = "diff{$diffType}";

        return $this->finished_at->$methodName($this->start_at);
    }

    public function getReportData()
    {
        return $this->userAgents()
            ->select([
                \DB::raw('count(id) AS count'),
                'device_type_id',
            ])
            ->groupBy('device_type_id')->get();
    }

    /**
     * Relationship with the Report model.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function report()
    {
        return $this->hasOne(Report::class);
    }

    public function getDownloadData()
    {
        return $this->userAgents()->with('deviceType')->get(['process_id', 'ua_string', 'device_type_id'])->toArray();
    }

    /**
     * Query scope "isExpiredScope".
     *
     * @param   Illuminate\Database\Query\Builder   $query
     * @return  Illuminate\Database\Query\Builder
     */
    public function scopeIsExpiredScope($query)
    {
        return $query->where('expires_at', '<', Carbon::now());
    }

    /**
     * Query scope "isNotFinishedScope".
     *
     * @param   Illuminate\Database\Query\Builder   $query
     * @return  Illuminate\Database\Query\Builder
     */
    public function scopeIsNotFinishedScope($query)
    {
        return $query->where('finished_at', null);
    }
}
