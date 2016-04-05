<?php

namespace App\Models;

use App\Models\DeviceType;
use App\Models\Process;

class UserAgent extends UuidModel
{
    protected $fillable = ['process_id', 'ua_string', 'device_type_id'];

    /**
     * Relationship with the Process model.
     *
     * @return    Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    /**
     * Relationship with the DeviceType model.
     *
     * @return    Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deviceType()
    {
        return $this->belongsTo(DeviceType::class);
    }
}
