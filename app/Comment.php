<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use App\Events\ActivityEvent;

class Comment extends Model
{
	use SoftDeletes, LogsActivity;

	protected $fillable = ['body', 'causer_id', 'causer_type'];

	protected static $logAttributes = ['body', 'causer_id', 'causer_type'];

    protected static $logName = 'system';

    public function tapActivity(Activity $activity, string $eventName)
    {
        ActivityEvent::dispatch($activity);
    }

	public function getDescriptionForEvent(string $eventName): string
    {
        return "A Comment has been {$eventName}";
    }

    public function commentable()
    {
    	return $this->morphTo();
    }

    public function causer()
    {
    	return $this->morphTo();
    }
}
