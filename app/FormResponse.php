<?php

namespace App;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;
use App\Events\ActivityEvent;

class FormResponse extends Model
{
    use LogsActivity;

    protected $fillable = ['form_id', 'user_id', 'data', 'ip_address', 'props'];

    protected static $logName = 'system';

    protected $casts = ['data' => 'array', 'props' => 'array'];

    protected static $logAttributes = ['data', 'props'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A form response has been {$eventName}";
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $description = $this->getDescriptionForEvent($eventName);
        ActivityEvent::dispatch($activity, $description);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}