<?php

namespace App;

use Bnb\Laravel\Attachments\HasAttachment;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;
use App\Events\ActivityEvent;

class FormResponse extends Model
{
    use LogsActivity, HasAttachment;

    protected $fillable = ['form_id', 'user_id', 'data', 'ip_address', 'props'];

    protected static $logName = 'system';

    protected $casts = ['data' => 'array', 'props' => 'array'];

    protected static $logAttributes = ['data', 'props'];

    /**
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "A form response has been {$eventName}";
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

     /**
     * Get the form link
     * @return string
     */
    public function getLinkAttribute()
    {
        return config('app.frontend_url').'/dashboard/forms/'.$this->form_id.'/responses';
    }

}