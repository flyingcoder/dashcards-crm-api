<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;
use App\Events\ActivityEvent;

class Form extends Model
{
    use SoftDeletes,
        Sluggable,
        LogsActivity;

    protected $fillable = ['title', 'status', 'questions', 'slug', 'user_id', 'company_id', 'props'];

    protected static $logName = 'system';

    protected $dates = ['deleted_at'];

    protected $casts = ['questions' => 'array', 'props' => 'array'];

    protected $appends = ['link'];

    protected static $logAttributes = ['title', 'status', 'questions', 'slug'];

    /**
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "A form has been {$eventName}";
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
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function responses()
    {
        return $this->hasMany(FormResponse::class, 'form_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sents()
    {
        return $this->hasMany(FormSent::class, 'form_id');
    }

    /**
     * Get the form link
     * @return string
     */
    public function getLinkAttribute()
    {
        return config('app.frontend_url') . '/form/' . $this->slug;
    }
}
