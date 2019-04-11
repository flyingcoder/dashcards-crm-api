<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \MaddHatter\LaravelFullcalendar\Event;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use App\Events\ActivityEvent;

class EventModel extends Model implements Event
{
	use SoftDeletes, LogsActivity;

    protected $table = 'events';

    protected static $logName = 'system';

    protected $fillable = [
    	'title', 
    	'calendar_id', 
    	'all_day',
    	'start',
    	'end',
    	'description',
    	'properties'
    ];

    protected $dates = ['start', 'end', 'deleted_at'];

    protected static $logAttributes = [
        'title', 
        'calendar_id', 
        'all_day',
        'start',
        'end',
        'description',
        'properties'
    ];

    public function tapActivity(Activity $activity, string $eventName)
    {
        $description = $this->getDescriptionForEvent($eventName);
        ActivityEvent::dispatch($activity, $description);
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A calendar event has been {$eventName}";
    }

    public function calendar()
    {
    	return $this->belongsTo(Calendar::class);
    }

    /**
     * Get the event's id number
     *
     * @return int
     */
    public function getId() {
		return $this->id;
	}

    /**
     * Get the event's title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Is it an all day event?
     *
     * @return bool
     */
    public function isAllDay()
    {
        return (bool)$this->all_day;
    }

    /**
     * Get the start time
     *
     * @return DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Get the end time
     *
     * @return DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }
}
