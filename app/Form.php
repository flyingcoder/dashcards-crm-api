<?php

namespace App;

use Auth;
use Illuminate\Http\Request;
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

    protected static $logAttributes = ['title', 'status', 'questions', 'slug'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A form has been {$eventName}";
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $description = $this->getDescriptionForEvent($eventName);
        ActivityEvent::dispatch($activity, $description);
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function responses()
    {
        return $this->hasMany(FormResponse::class, 'form_id');
    }

    public function sents()
    {
        return $this->hasMany(FormSent::class, 'form_id');
    }
}
