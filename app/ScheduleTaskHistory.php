<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduleTaskHistory extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'props' => 'array'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scheduleTask()
    {
        return $this->belongsTo(ScheduleTask::class);
    }
}
