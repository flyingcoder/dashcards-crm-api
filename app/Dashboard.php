<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;
use App\Events\ActivityEvent;

class Dashboard extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = ['title', 'email', 'description'];

    protected static $logName = 'system';

    protected static $logAttributes = ['title', 'email', 'description'];

    /**
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "A Dashboard has been {$eventName}";
    }

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function dashitems()
    {
        return $this->belongsToMany(Dashitem::class)->withPivot('order', 'visible');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @param $order
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function itemByOrder($order)
    {
        return $this->belongsToMany(Dashitem::class)->wherePivot('order', 1);
    }
}
