<?php

namespace App\Models;

use App\Models\UserAgent;
use Carbon\Carbon;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class Process extends UuidModel implements HasMedia
{
    use HasMediaTrait;

    protected $fillable = ['email', 'finished_at', 'expires_at', 'start_at', 'finished_at'];

    protected $dates = ['expires_at', 'finished_at', 'start_at'];

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
        return $this->finished_at->isPast();
    }

}
