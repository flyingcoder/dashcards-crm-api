<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

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

    protected static $logAttributes = [
        'user_id', 'name' 
    ];

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
