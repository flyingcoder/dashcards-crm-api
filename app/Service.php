<?php

namespace App;

use App\Company;
use App\Events\ActivityEvent;
use App\Project;
use App\Scopes\ServiceScope;
use App\Traits\HasMediaLink;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Plank\Metable\Metable;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Service extends Project
{
    protected $table = 'projects';
    protected $paginate = 10;
    protected static $logName = 'project';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title', 'started_at', 'end_at', 'description', 'status', 'company_id', 'type', 'props'
    ];

    protected static $logAttributes = [
        'title', 'started_at', 'end_at', 'status', 'company_id'
    ];

    protected $casts = [
        'props' => 'array'
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
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
    
    public function forms()
    {
        return $this->belongsToMany(Form::class);
    }

    public function team()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id')->withPivot('role');
    }

    public function managers()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id')->wherePivot('role', 'Manager');
    }

    public function client()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id')->wherePivot('role', 'Client');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id')->wherePivot('role', 'Members');
    }

    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ServiceScope);
    }
}
