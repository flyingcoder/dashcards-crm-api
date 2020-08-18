<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;
use App\Events\ActivityEvent;

class Comment extends Model
{
	use SoftDeletes, LogsActivity;

	protected $fillable = ['body', 'causer_id', 'causer_type'];

	protected static $logAttributes = ['body', 'causer_id', 'causer_type'];

    protected static $logName = 'system';

    protected $appends = ['hover'];
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
        return "A Comment has been {$eventName}";
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable()
    {
    	return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function causer()
    {
    	return $this->morphTo();
    }

    public function getHoverAttribute()
    {
        return false;
    }
}
