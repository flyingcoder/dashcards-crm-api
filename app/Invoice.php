<?php

namespace App;

use App\Events\ActivityEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Plank\Metable\Metable;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Invoice extends Model implements HasMedia
{
    use SoftDeletes,
        HasMediaTrait,
        LogsActivity,
        Metable;

    protected $fillable = [
        'date',
        'user_id',
        'discount',
        'title', 
        'project_id',
        'due_date',
        'items',
        'total_amount',
        'terms',
        'tax',
        'due_date',
        'billed_from',
        'billed_to',
        'type',
        'company_logo',
        'status',
        'props'
    ];

    protected static $logName = 'system';

    protected static $logAttributes = [
        'date',
        'user_id',
        'discount',
        'title', 
        'project_id',
        'due_date',
        'items',
        'total_amount',
        'terms',
        'tax',
        'due_date',
        'billed_from',
        'billed_to',
        'type',
        'company_logo',
        'status',
        'props'
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
        return "A invoice has been {$eventName}";
    }

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
    	return $this->belongsTo(Project::class);
    }

    public function billedTo()
    {
        return $this->hasOne(User::class, 'id', 'billed_to');
    }

    public function billedFrom()
    {
        return $this->hasOne(User::class, 'id', 'billed_from');
    }
}
