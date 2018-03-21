<?php

namespace App;

use Auth;
use DB;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Media;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;


class Project extends Model implements HasMediaConversions
{
    use SoftDeletes, HasMediaTrait, LogsActivity;

    protected $paginate = 10;

    protected $fillable = [
        'location', 'started_at', 'service_id', 'end_at', 'description', 'status'
    ];

    protected static $logAttributes = [
        'location', 'started_at', 'service_id', 'end_at', 'description', 'status'
    ];

    protected $dates = ['deleted_at'];

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
                        DB::raw('CONCAT(users.last_name, ", ", users.first_name) AS assignee'),
                        'tasks.*')
                      ->where('tasks.deleted_at', null);

        if($request->has('sort')) {

            list($sortName, $sortValue) = parseSearchParam($request);

            $tasks->orderBy($sortName, $sortValue);
        }

        return $tasks->paginate($this->paginate);
        
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

        if($request->has('sort')) {

            list($sortName, $sortValue) = parseSearchParam($request);

            $tasks->orderBy($sortName, $sortValue);
        }

        return $tasks->paginate($this->paginate);
    }
    
    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }

    public function progress()
    {
        $percentage = 0;
        foreach ($this->milestone as $key => $value) {
            $percentage = $value->percentage + $percentage;
        }
        return $percentage;
    }

    public function company()
    {
        return $this->manager->first()->company();
    }

    public function members()
    {
        return $this->belongsToMany(User::class)->withPivot('role');
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
        return $this->belongsToMany(User::class)->wherePivot('role', 'Manager');
    }

    //not being used as of the moment reffer to api/user/projects
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
    }

    /**
     *
     * boot events log activity per events
     *
     */
    

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
    }
}
