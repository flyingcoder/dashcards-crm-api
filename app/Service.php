<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;
use App\Events\ActivityEvent;

class Service extends Model
{
    use SoftDeletes, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name' 
    ];

    protected static $logName = 'system';

    protected static $logAttributes = [
        'user_id', 'name' 
    ];

    public function tapActivity(Activity $activity, string $eventName)
    {
        $description = $this->getDescriptionForEvent($eventName);
        ActivityEvent::dispatch($activity, $description);
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A service has been {$eventName}";
    }

    public function company()
    {
        return $this->user->company();
    }

    public function projects()
    {
    	return $this->hasMany(Project::class);
    }
    
    public function forms()
    {
        return $this->belongsTo(Form::class);
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    /*
    public static function boot() 
    {
        if(!is_null(Auth::user())) {
            Service::created(function ($service) {
                activity(Auth::user()->company()->name)
                   ->performedOn($service)
                   ->causedBy(Auth::user())
                   ->log('Created');
            });

            Service::deleted(function ($service) {
                activity(Auth::user()->company()->name)
                   ->performedOn($service)
                   ->causedBy(Auth::user())
                   ->log('Deleted');
            });

            Service::saved(function ($service) {
                activity(Auth::user()->company()->name)
                   ->performedOn($service)
                   ->causedBy(Auth::user())
                   ->log('Updated');
            });
        }
        
        Service::deleting(function($service) {
            foreach(['projects'] as $relation)
            {
                foreach($service->{$relation} as $item)
                {
                    $item->delete();
                }
            }
        });
    }*/
}
