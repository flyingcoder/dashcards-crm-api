<?php

namespace App;

use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Media;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;


class Project extends Model implements HasMediaConversions
{
    use SoftDeletes, HasMediaTrait;

    protected $paginate = 10;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title', 'started_at', 'service_id', 'end_at', 'description', 'status', 'company_id'
    ];

    protected static $logAttributes = [
        'title', 'started_at', 'service_id', 'end_at', 'description', 'status', 'company_id'
    ];

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function progress()
    {
        /*
        $percentage = 0;
        foreach ($this->milestone as $key => $value) {
            $percentage = $value->percentage + $percentage;
        }
        return $percentage;
        */

        $tasks = $this->tasks();

        $all = $tasks->count();

        $done = $tasks->where('tasks.status', 'closed')->count();

        $percentage = 0;
        
        if($all != 0)
            $percentage = round(($done/$all)*100, 2);

        return $percentage;
    }

    public function totalTime()
    {
        $project = $this;

        $tasks = $project->tasks;

        $proj_total_sec = 0;

        if(empty($tasks))
            return gmdate("H:i:s", $proj_total_sec);

        foreach ($tasks as $key => $task) {

            $lastTimer = $task->lastTimer();    

            $con = is_object($lastTimer);

            if($con && $lastTimer->action == "start"){

                $start = Carbon::parse($lastTimer->created_at);

                $end = Carbon::now();

                $task_total_sec = (int) $end->diffInSeconds($start);

                $proj_total_sec = $proj_total_sec + $task_total_sec;

            } else if($con && $lastTimer->action == 'back') {
                
                $open_timers = $task->timers()
                                ->where('status', 'open');

                $last_pause_timer = $open_timers->where('action', 'pause')
                                                ->latest()
                                                ->first();

                if(empty($last_pause_timer)) //this will not be empty but in case
                    return response('No pause action before back!', 500);

                $start = Carbon::parse($last_pause_timer->created_at);

                $last_pause_timer = json_decode($last_pause_timer->properties);

                $end = Carbon::now();

                $task_total_sec = (int) $end->diffInSeconds($start);

                //added the start to pause total
                $task_total_sec = $task_total_sec + $last_pause_timer->total_seconds;

                //sum up to other task total timer
                $proj_total_sec = $proj_total_sec + $task_total_sec;
            }
        }

        return gmdate("H:i:s", $proj_total_sec);
    }

    /**
     *
     * Media library image convertion
     *
     */

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
              ->width(368)
              ->height(232)
              ->sharpen(10);
    }
    
    public function service()
    {
    	return $this->belongsTo(Service::class);
    }

    public function tasks()
    {
        return $this->hasManyThrough(Task::class, Milestone::class);
    }

    public function paginatedProjectTasks(Request $request)
    {
        $tasks = $this->tasks()
                      ->join('task_user as tu', 'tu.task_id', '=', 'tasks.id')
                      ->join('users', 'users.id', '=', 'tu.user_id')
                      ->select(
                        'users.image_url as image',
                       DB::raw('CONCAT(CONCAT(UCASE(LEFT(users.last_name, 1)), SUBSTRING(users.last_name, 2)), ", ", CONCAT(UCASE(LEFT(users.first_name, 1)), SUBSTRING(users.first_name, 2))) AS assignee'),
                       'tasks.*')
                      ->where('tasks.deleted_at', null);

        if( $request->has('sort') && !empty(request()->sort) ) {

            list($sortName, $sortValue) = parseSearchParam($request);

            $tasks->orderBy($sortName, $sortValue);
        }



        if(request()->has('all') && request()->all)
            $data = $tasks->get();
        else
            $data = $tasks->paginate($this->paginate);

        $data->map(function ($model) {
            dd($model);
            $model['total_time'] = $model->total_time();

            return $model;

        });
        
    }

    public function paginatedProjectMyTasks(Request $request)
    {
        $tasks = $this->tasks()
                      ->join('task_user as tu', 'tu.task_id', '=', 'tasks.id')
                      ->join('users', 'users.id', '=', 'tu.user_id')
                      ->where('users.id', auth()->user()->id)
                      ->select(
                        DB::raw('CONCAT(users.last_name, ", ", users.first_name) AS assignee'),
                        'tasks.*')
                      ->where('tasks.deleted_at', null);

        if($request->has('sort') && !empty($request->sort)) {

            list($sortName, $sortValue) = parseSearchParam($request);

            $tasks->orderBy($sortName, $sortValue);
        }

        if(request()->has('all') && request()->all)
            return $tasks->get();

        return $tasks->paginate($this->paginate);
    }
    
    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class)->withPivot('role');
    }

    public function paginatedMembers()
    {
        list($sortName, $sortValue) = parseSearchParam(request());

        $model = $this->members();
        $table = 'users';

        if(request()->has('sort') && !empty(request()->sort))
            $model->orderBy($sortName, $sortValue);

        if(request()->has('search')){
            $keyword = request()->search;
            $model->where(function ($query) use ($keyword, $table) {
                        $query->where("{$table}.first_name", "like", "%{$keyword}%");
                        $query->where("{$table}.last_name", "like", "%{$keyword}%");
                        $query->where("{$table}.email", "like", "%{$keyword}%");
                        $query->where("{$table}.telephone", "like", "%{$keyword}%");
                        $query->where("{$table}.job_title", "like", "%{$keyword}%");
                  });
        }

        return $model->with('tasks')->paginate($this->paginate);

    }

    public function client()
    {
        return $this->belongsToMany(User::class)
                    ->wherePivot('role', 'Client');
    }

    public function getClient()
    {
        return $this->client()->first();
    }

    public function manager()
    {
        return $this->belongsToMany(User::class)
                    ->wherePivot('role', 'Manager');
    }

    public function getManager()
    {
        return $this->manager()->first();
    }

    //not being used as of the moment reffer to api/user/projects
    /*
    public static function personal(Request $request)
    {
        list($sortName, $sortValue)  = parseSearchParam(request());

        $projects = Auth::user()->projects()
                    ->join('services', 'services.id', '=', 'projects.service_id')
                    ->join('project_user as manager_pivot', function ($join) {
                        $join->on('manager_pivot.project_id', '=', 'projects.id')
                             ->where('manager_pivot.role', '=', 'Manager');
                    })
                    ->join('users as manager', 'manager_pivot.user_id', '=', 'manager.id')
                    ->join('project_user as client_pivot', function ($join) {
                        $join->on('client_pivot.project_id', '=', 'projects.id')
                             ->where('client_pivot.role', '=', 'Client');
                    })
                    ->join('users as client', 'client_pivot.user_id', '=', 'client.id')
                    ->with('milestones')
                    ->select(
                        DB::raw('CONCAT(manager.last_name, ", ", manager.first_name) AS manager_name'),
                        'client.image_url as client_image_url',
                        DB::raw('CONCAT(client.last_name, ", ", client.first_name) AS client_name'),
                        'projects.*',
                        'services.name as service_name'
                    )->where('projects.deleted_at', null);

        if($request->has('status'))
            $projects->where('status', $request->status);

        if($request->has('sort'))
            $projects->orderBy($sortName, $sortValue);
        
        return $projects->paginate(10);
    }*/

    /**
     *
     * boot events log activity per events
     *
     */
    
    /*
    public static function boot()
    {
        if(!is_null(Auth::user())) {
            Project::created(function ($project) {
                activity(Auth::user()->company()->name)
                   ->performedOn($project)
                   ->causedBy(Auth::user())
                   ->withProperties(['company_id', Auth::user()->company()->id])
                   ->log('Created');
            });

            Project::deleted(function ($project) {
                activity(Auth::user()->company()->name)
                   ->performedOn($project)
                   ->causedBy(Auth::user())
                   ->withProperties(['company_id', Auth::user()->company()->id])
                   ->log('Deleted');
            });

            Project::saved(function ($project) {
                activity(Auth::user()->company()->name)
                   ->performedOn($project)
                   ->causedBy(Auth::user())
                   ->withProperties(['company_id', Auth::user()->company()->id])
                   ->log('Updated');
            });
        }
        
        Project::deleting(function($project) {
            foreach(['milestones'] as $relation)
            {
                foreach($project->{$relation} as $item)
                {
                    $item->delete();
                }
            }
        });
    }*/
}
