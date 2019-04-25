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

    protected $fillable = ['title', 'status', 'questions', 'slug', 'user_id'];

    protected static $logName = 'system';

    protected $dates = ['deleted_at'];

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

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public static function store(Request $request)
    {

    	request()->validate([
    		'questions' => 'required',
    		'title' => 'required'
    	]);

    	return Auth::user()->forms()->create([
    		'title' => $request->title,
    		'questions' => $request->questions,
    		'status' => 'Enabled'
    	]);
    }
}
