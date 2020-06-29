<?php

namespace App;

use App\Campaign;
use App\Events\ActivityEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class ServiceList extends Model
{
    use SoftDeletes, LogsActivity;  
    
    protected $table = 'services';
	protected $paginate = 10;
	protected static $logName = 'service_list';
	protected $fillable = [
        'name', 'description', 'status', 'company_id', 'icon', 'type', 'props', 'user_id'
    ];
	
	protected static $logAttributes = [
        'name',  'description', 'status', 'company_id', 'type', 'props', 'icon'
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
        return $this->belongsTo(Company::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'service_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

}
