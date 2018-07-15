<?php

namespace App;

use Auth;
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
        'title', 'description', 'milestone_id', 'started_at', 'end_at', 'status', 'days'
    ];

    protected $dates = ['deleted_at'];

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
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
        if(!empty($request->members)){
            foreach($request->members as $m){
                $task->assigned()->attach($m);
            }
        }
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

    public function company()
    {
      return $this->milestone->project->company();
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
