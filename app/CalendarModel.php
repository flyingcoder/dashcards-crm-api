<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;
use App\Events\ActivityEvent;

class CalendarModel extends Model
{
	use SoftDeletes, LogsActivity;

	protected $fillable = [
    	'title', 
    	'company_id',
    	'description',
    	'properties'
    ];

    protected $table = 'calendars';

    protected $dates = ['deleted_at'];

    protected static $logAttributes = [
        'title', 
        'company_id',
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
        return "A calendar has been {$eventName}";
    }

    public function company()
    {
    	return $this->belongsTo(Company::class);
    }

    public function events()
    {
    	return $this->hasMany(EventModel::class);
    }
}
