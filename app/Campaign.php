<?php

namespace App;

use App\Events\ActivityEvent;
use App\Scopes\CampaignScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Plank\Metable\Metable;
use Spatie\Activitylog\Contracts\Activity;

//#noteby:kirby: Service is Campaign which has same table with projects, and service table is ServiceList  
class Campaign extends Project
{
    use SoftDeletes, Metable;
    protected $table = 'projects';
    protected $paginate = 10;
    protected static $logName = 'project';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title', 'started_at', 'end_at', 'description', 'status', 'company_id', 'type', 'props', 'service_id'
    ];

    protected static $logAttributes = [
        'title', 'started_at', 'end_at', 'status', 'company_id'
    ];

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
        return "A campaign has been {$eventName}";
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function forms()
    {
        return $this->belongsToMany(Form::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function team()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id')->withPivot('role');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function managers()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id')->wherePivot('role', 'Manager');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function client()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id')->wherePivot('role', 'Client');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id')->wherePivot('role', 'Members');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service()
    {
        return $this->belongsTo(ServiceList::class);
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CampaignScope);
    }

}
