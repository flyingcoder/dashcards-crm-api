<?php

namespace App;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;
use App\Events\ActivityEvent;

class FormSent extends Model
{
    use LogsActivity;

    protected $table = 'form_sent';

    protected $fillable = ['form_id', 'user_id', 'props'];

    protected static $logName = 'system';

    protected $casts = [ 'props' => 'array'];

    protected static $logAttributes = [ 'props'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A form sent has been {$eventName}";
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