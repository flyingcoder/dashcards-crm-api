<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;
use App\Events\ActivityEvent;
use Spatie\MediaLibrary\Media;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class Invoice extends Model implements HasMedia
{
    use SoftDeletes,
        HasMediaTrait,
        LogsActivity;

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
        'company_logo'
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
        'company_logo'
    ];

    public function tapActivity(Activity $activity, string $eventName)
    {
        ActivityEvent::dispatch($activity);
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
}
