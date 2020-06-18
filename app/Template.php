<?php

namespace App;

use App\Events\ActivityEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Plank\Metable\Metable;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Template extends Model
{
	use SoftDeletes, LogsActivity, Metable;

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'status', 'name', 'replica_type'
    ];

    protected static $logName = 'system';
    
    protected static $logAttributes = [
        'company_id', 'status', 'name', 'replica_type'
    ];

    public function tapActivity(Activity $activity, string $eventName)
    {
        $description = $this->getDescriptionForEvent($eventName);
        ActivityEvent::dispatch($activity, $description);
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A Template has been {$eventName}";
    }

    protected $dates = ['deleted_at'];

    public function milestones()
    {
        return $this->belongsToMany(Milestone::class);
    }
    
    public function company()
    {
    	return $this->belongsTo(Company::class);
    }
}
