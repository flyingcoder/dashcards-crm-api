<?php

namespace App;

use Auth;
use Kodeine\Acl\Models\Eloquent\Role;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Task extends Model
{
    use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'title', 'description', 'milestone_id', 'started_at', 'end_at', 'status', 'days', 'role_id'
    ];

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

        $this->save();

        return $this;
    }

    public static function store(Request $request)
    {
        if($request->started_at != null){
            $request->validate([
                'end_at' => 'after:started_at',
            ]);
            $started_at = $request->started_at;
            $end_at = $request->end_at;
        }
        else{
            $started_at = date("Y-m-d",strtotime("now"));
            $end_at = date("Y-m-d",strtotime($request->days . ' days'));
        }
        
        $task = self::create([
            'title' =>$request->title,
            'description' =>$request->description,
            'milestone_id' =>$request->milestone_id,
            'started_at' =>$started_at,
            'end_at' =>$end_at,
            'status' => 'Open'
        ]);

        if(!empty($request->members))
            $task->assigned()->attach($request->members);

        return [$task->save(), $task];
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
        if(is_null($this->lastTimer()))
            return "00:00:00";

        $timer = $this->lastTimer();

        if($timer->action == 'start')
            return $this->fromNow($timer->action);

        $properties = json_decode($timer->properties);

        return $properties->total_time;
    }

    public function fromNow($started_at)
    {
        $start = Carbon::parse($started_at);

        $end = Carbon::now();

        $total_sec = $end->diffInSeconds($start);

        return gmdate("H:i:s", $total_sec);
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
