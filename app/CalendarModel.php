<?php

namespace App;

use App\Company;
use App\EventModel;
use App\Events\ActivityEvent;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class CalendarModel extends Model
{
	use SoftDeletes, LogsActivity;

	protected $fillable = [
    	'title', 
    	'company_id',
    	'description',
    	'properties',
        'user_id'
    ];

    protected $table = 'calendars';

    protected $dates = ['deleted_at'];

    protected static $logAttributes = [
        'title', 
        'company_id',
        'description',
        'properties',
        'user_id'
    ];

    protected $casts = [
        'properties' => 'array'
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
    	return $this->belongsTo(Company::class, 'id', 'company_id');
    }

    public function events()
    {
    	return $this->hasMany(EventModel::class, 'calendar_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
