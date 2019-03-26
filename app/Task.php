<?php

namespace App;

use Carbon\Carbon;
use Auth;
use Kodeine\Acl\Models\Eloquent\Role;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Events\ActivityEvent;

class Task extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'title', 'description', 'milestone_id', 'started_at', 'end_at', 'status', 'days', 'role_id'
    ];

    protected static $logAttributes = [
        'title', 'description', 'milestone_id', 'started_at', 'end_at', 'status', 'days', 'role_id'
    ];

    protected static $logName = 'system';

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A task has been {$eventName}";
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        ActivityEvent::dispatch($activity);
    }

    protected $dates = ['deleted_at'];

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function assigned_to()
    {
        return $this->belongsTo(Role::class);
    }

    public function updateTask()
    {
        $this->title = request()->title;

        $this->description = request()->description;

        if(request()->has('days'))
            $this->days = request()->days;

        if(request()->has('started_at'))
            $this->started_at = request()->started_at;

        if(request()->has('end_at'))
            $this->end_at = request()->end_at;

        if(request()->has('role_id'))
            $this->role_id = request()->role_id;

        $this->save();

        if(request()->has('assigned_id') && !empty(request()->assigned_id)) {
            $this->assigned()->sync(request()->assigned_id);

            $this->assigned_id = request()->assigned_id[0];

            $user = User::findOrFail(request()->assigned_id[0]);

            $this->assignee_url = $user->image_url;
        }

        return $this;
    }

    public static function store()
    {
        if(request()->started_at != null){
            request()->validate([
                'end_at' => 'after:started_at',
            ]);
            $started_at = request()->started_at;
            $end_at = request()->end_at;
        }
        else{
            $started_at = date("Y-m-d",strtotime("now"));
            $end_at = date("Y-m-d",strtotime(request()->days . ' days'));
        }
        
        $task = self::create([
            'title' => request()->title,
            'description' => request()->description,
            'milestone_id' => request()->milestone_id,
            'started_at' =>$started_at,
            'end_at' =>$end_at,
            'status' => 'Open'
        ]);

        $task->save();

        if(request()->has('assigned_ids')) {
            $task->assigned()->sync(request()->assigned_ids);

            $task->assigned_ids = request()->assigned_ids;

            $user = User::findOrFail(request()->assigned_ids[0]);

            $task->assignee_url = $user->image_url;
        }

        return $task;
    }

    public function timers()
    {
        return $this->morphMany(Timer::class, 'subject');
    }

    public function lastTimer()
    {
        return $this->timers()
                    ->latest()
                    ->first();
    }

    //in order to count all time from a task action should always start and pause.
    public function total_time()
    {
        $total_sec = $this->totalSec();

        return gmdate("H:i:s", $total_sec);
    }

    public function totalSec()
    {
        if(is_null($this->lastTimer()))
            return 0;

        $last_timer = $this->lastTimer();

        if($last_timer->action == 'start')
            return $this->fromNow($last_timer->created_at);


        $model = $this;
        
        $open_timer = $model->timers()
                            ->where('status', 'open');

        $total_sec = 0;

        if($last_timer->action == 'back') {
            
            $start = Carbon::parse($last_timer->created_at);

            $end = Carbon::now();

            $total_sec = $end->diffInSeconds($start);
        }

        $paused_timer = $open_timer->where('action', 'pause')->get();

        foreach ($paused_timer as $value) {
            $properties = json_decode($value->properties);
            $total_sec = $total_sec + intval($properties->total_seconds);
        }

        return $total_sec;
    }

    public function timerStatus()
    {
        if(is_null($this->lastTimer()))
            return "stop";

        $task = $this->lastTimer();

        if( $task->action == 'start' || $task->action == 'back' )
            return "ongoing";
        else
            return $task->action;
    }

    public function fromNow($started_at)
    {
        $start = Carbon::parse($started_at);

        $end = Carbon::now();

        $total_sec = $end->diffInSeconds($start);

        return $total_sec;
    }

    public function company()
    {
      return $this->milestone->project->company();
    }

    public function project()
    {
        return $this->milestone->project;
    }

    public function milestone()
    {
    	return $this->belongsTo(Milestone::class);
    }

    public function assigned()
    {
    	return $this->belongsToMany(User::class)
                    ->withTimestamps()
                    ->withPivot('created_at');
    }

    /*
    public static function boot()
    {
        
        if(!is_null(Auth::user())) {
            Task::created(function ($task) {
                activity(Auth::user()->company())
                   ->performedOn($task)
                   ->causedBy(Auth::user())
                   ->log('Created');
            });

            Task::deleted(function ($task) {
                activity(Auth::user()->company())
                   ->performedOn($task)
                   ->causedBy(Auth::user())
                   ->log('Deleted');
            });

            Task::saved(function ($task) {
                activity(Auth::user()->company())
                   ->performedOn($task)
                   ->causedBy(Auth::user())
                   ->log('Updated');
            });
        }
    }*/
}
