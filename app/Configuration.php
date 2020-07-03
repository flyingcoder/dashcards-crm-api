<?php

namespace App;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;
use App\Events\ActivityEvent;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
	use LogsActivity;

    protected $table = 'configurations';
    protected static $logName = 'system';
	protected static $logAttributes = ['type', 'value', 'key'];
	protected $fillable = ['type', 'value', 'key'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A configurations has been {$eventName}";
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $description = $this->getDescriptionForEvent($eventName);
        ActivityEvent::dispatch($activity, $description);
    }
}
