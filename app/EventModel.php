<?php

namespace App;

use App\CalendarModel;
use App\EventParticipant;
use App\EventType;
use App\Events\ActivityEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use \MaddHatter\LaravelFullcalendar\Event;

class EventModel extends Model implements Event
{
	use SoftDeletes, LogsActivity;

    protected $table = 'events';

    protected static $logName = 'system';

    protected $fillable = [
    	'title', 
    	'all_day',
    	'start',
    	'end',
    	'description',
    	'properties',
        'eventtypes_id'
    ];

    protected $dates = [ 'deleted_at'];

    protected static $logAttributes = [
        'title', 
        'all_day',
        'start',
        'end',
        'description',
        'properties'
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'properties' => 'array',
        'all_day' => 'boolean'
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

    public function participants()
    {
        return $this->hasMany(EventParticipant::class, 'event_id', 'id');
    }

    public function eventType()
    {
        return $this->belongsTo(EventType::class, 'eventtypes_id', 'id');
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

/*
properties {
    type : private || public,
    creator : user_id,
    send_alarm : true|false,

}
*/