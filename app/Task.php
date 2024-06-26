<?php

namespace App;

use App\Events\ActivityEvent;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Kodeine\Acl\Models\Eloquent\Role;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Task extends Model
{
    use SoftDeletes, LogsActivity, Searchable ;

    protected $fillable = [
        'title', 'description', 'milestone_id', 'started_at', 'end_at', 'status', 'days', 'role_id'
    ];

    protected static $logAttributes = [
        'title', 'description', 'milestone_id', 'started_at', 'end_at', 'status', 'days', 'role_id'
    ];

    protected static $logName = 'system';

    protected $allowed_status = ['open', 'completed', 'pending', 'behind'];

    public function toArray() {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'project_id' => $this->project()->id ?? 0,
            'milestone_id' => $this->milestone_id,
            'status' => $this->status,
            'days' => $this->days,
            'total_time' => $this->total_time(),
            'assignee' => $this->assigned,
            'description' => $this->description,
            'started_at' => $this->started_at,
            'end_at' => $this->end_at,
        ];
    }
    
    public function getDescriptionForEvent(string $eventName): string
    {
        return "A task has been {$eventName}";
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
       $description = $this->getDescriptionForEvent($eventName);
        ActivityEvent::dispatch($activity, $description);
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

        $user_ids = [];

        if(request()->has('assigned') && !empty(request()->assigned)) {

            if(is_array(request()->assigned) && !empty(request()->assigned)) {
                foreach (request()->assigned as $key => $value) {
                    if (is_array($value) && array_key_exists('id', $value)) { //[['id'=>2, 'name' =>'sample']]
                        $user_ids[] = $value['id'];
                    } elseif (gettype($value) === 'object') { //[{'id':2, 'name':'sample'}]
                        $user_ids[] = $value->id;
                    } else {    //[ 2, 3, 4]
                        $user_ids[] = $value;
                    }
                }
            }

            $this->assigned()->sync($user_ids);

        }

        return $this->toArray();
    }

    public static function store()  
    {
        if(request()->started_at != null){
            request()->validate([
                'end_at' => 'after_or_equal:started_at',
            ]);
            $started_at = request()->started_at;
            $end_at = request()->end_at;
            $days = round((strtotime($end_at) - strtotime($started_at)) / (60 * 60 * 24));
        }
        else{
            $started_at = date("Y-m-d",strtotime("now"));
            $end_at = date("Y-m-d",strtotime(request()->days . ' days'));
            $days = request()->days;
        }
        
        $task = self::create([
            'title' => request()->title,
            'description' => request()->description,
            'milestone_id' => request()->milestone_id,
            'started_at' =>$started_at,
            'end_at' =>$end_at,
            'status' => 'Open',
            'days' => $days
        ]);

        $task->save();

        if(request()->has('assigned')) {
            $task->assigned()->sync(request()->assigned);

            $task->assigned_ids = request()->assigned;
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
            $properties = $value->properties;
            $total_sec = $total_sec + intval($properties['total_seconds']);
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
        return $this->milestone ? $this->milestone->project : null;
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

    public function markStatus($status = 'open')
    {
        if (in_array(strtolower($status), $this->allowed_status)) {
            $this->status = strtolower($status);
            $this->save();
        }
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
    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();
        // Customize array...
        return $array;
    }
}
