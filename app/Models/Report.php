<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes;

    protected $fillable = ['process_id', 'total', 'desktop', 'tablet', 'mobile', 'other', 'unknown'];

    /**
     * Relationship with the Process model.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function process()
    {
        return $this->belongsTo(Process::class);
    }
}
