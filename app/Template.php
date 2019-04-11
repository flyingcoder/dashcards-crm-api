<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;
use App\Events\ActivityEvent;

class Template extends Model
{
	use SoftDeletes, LogsActivity;

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
        ActivityEvent::dispatch($activity);
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
