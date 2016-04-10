<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class UserAgent extends UuidModel
{
    use SoftDeletes;

    protected $fillable = ['process_id', 'ua_string', 'device_type_id'];

    protected $appends = ['device_type_name'];

    /**
     * Retrieve the deviceTypeName attribute.
     *
     * @param   mixed
     *
     * @return string
     */
    public function getDeviceTypeNameAttribute($value)
    {
        if ($this->deviceType) {
            return $this->deviceType->name;
        }

        return 'not parsed yet';
    }

    /**
     * Relationship with the Process model.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    /**
     * Relationship with the DeviceType model.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deviceType()
    {
        return $this->belongsTo(DeviceType::class);
    }

    /**
     * Query scope "notProcessed".
     *
     * @param Illuminate\Database\Query\Builder $query
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeNotProcessed($query)
    {
        return $query->where('device_type_id', 0);
    }

    /**
     * Query scope "processed".
     *
     * @param Illuminate\Database\Query\Builder $query
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeProcessed($query)
    {
        return $query->where('device_type_id', '!=', 0);
    }

    public function isProcessed()
    {
        return $this->device_type_id === 0;
    }
}
