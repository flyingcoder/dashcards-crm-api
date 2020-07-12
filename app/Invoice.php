<?php

namespace App;

use App\Events\ActivityEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
        'props',
        'parent',
        'is_recurring'
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
        'props',
        'parent',
        'is_recurring'
    ];
    protected $dates = ['deleted_at'];

    protected $casts = [
        'props' => 'array'
    ];

    /**
     * @param Activity $activity
     * @param string $eventName
     */
    public function tapActivity(Activity $activity, string $eventName)
    {
        $description = $this->getDescriptionForEvent($eventName);
        ActivityEvent::dispatch($activity, $description);
    }

    /**
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "A invoice has been {$eventName}";
    }

    /**
     * Get the invoice link
     * @return string
     */
    public function getLinkAttribute()
    {
        return config('app.frontend_url') . '/dashboard/invoices?view=true&id=' . $this->id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function billedTo()
    {
        return $this->hasOne(User::class, 'id', 'billed_to');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function billedFrom()
    {
        return $this->hasOne(User::class, 'id', 'billed_from');
    }
}
