<?php

namespace App;

use App\Events\ActivityEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Plank\Metable\Metable;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class ScheduleTask extends Model
{
    use SoftDeletes, Metable, LogsActivity;

    protected $guarded = ['id'];
    protected static $logName = 'system.schedule-task';
    protected static $logOnlyDirty = true;
    protected static $logAttributes = [
        'schedule_type',  'interval_type',  'interval_at', 'props', 'deleted_at', 'timezone', 'last_run_at', 'next_run_at'
    ];
    protected $casts = [
        'props' => 'array'
    ];

    /**
     * @param Activity $activity
     * @param string $eventName
     */
    public function tapActivity(Activity $activity, string $eventName)
    {
        $description = $this->getDescriptionForEvent($eventName);
        ActivityEvent::dispatch($activity, $description);
    }

    /**
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "An schedule task has been {$eventName}";
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function histories()
    {
        return $this->hasMany(ScheduleTaskHistory::class);
    }
}
