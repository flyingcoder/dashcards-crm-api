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

    public function importMilestones()
    {
        $template = Template::findOrFail(request()->template_id);

        //get milestones
        if($template->milestones->count() <= 0)
            return response(['message' => 'Template has no milestones.'], 500);

        foreach ($template->milestones as $key => $milestone) {

            $new_milestone = $milestone->replicate();

            $new_milestone->project_id = $this->id;

            $new_milestone->save();

            if($milestone->tasks->count() > 0) {

                foreach ($milestone->tasks as $key => $task) {

                   $new_task = $new_milestone->tasks()->create([
                        'title' => $task->title,
                        'description' => $task->description,
                        'status' => $task->status,
                        'days' => $task->days
                   ]);

                   if(empty($task->role_id)) {

                        $role = Role::findOrFail($task->role_id);

                        foreach ($role->users as $key => $user) {

                           $new_task->assigned()->attach($member);

                            $project = $new_milestone->project;

                            $project->members()->attach($member);
                        }

                   }

                   
                }

            }
        }
    }

    public function storeInvoice()
    {
        $invoice = $this->invoices()->create(request()->all());

        $client = $this->getClient();

        $invoice->bill_to = ucfirst($client->last_name) . ', ' . ucfirst($client->first_name);

        $invoice->bill_from = ucfirst(auth()->user()->last_name) . ', ' . ucfirst(auth()->user()->first_name);

        return $invoice;
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
            $proj_total_sec = $proj_total_sec + $task->totalSec();
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

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        $data = $tasks->paginate($this->paginate);

        if(request()->has('all') && request()->all)
            $data = $tasks->get();
        

        $data->map(function ($model) {
            $model['total_time'] = $model->total_time();

            return $model;

        });

        return $data;
        
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

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        return $tasks->paginate($this->paginate);
    }
    
    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }

    public function paginatedInvoices()
    {
        $model = $this->invoices()
                      ->where('invoices.deleted_at', null);

        if(request()->has('sort') && !empty(request()->sort)) {

            list($sortName, $sortValue) = parseSearchParam(request());

            $model->orderBy($sortName, $sortValue);
        }

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        $data = $model->paginate($this->paginate);

        if(request()->has('all') && request()->all)
            $data = $model->get();

        $data->map(function ($model) {

            $client = $this->getClient();

            $model['bill_to'] = ucfirst($client->last_name) . ', ' . ucfirst($client->first_name);

            $model['bill_from'] = ucfirst(auth()->user()->last_name) . ', ' . ucfirst(auth()->user()->first_name);

            return $model;
        });

        return $data;
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

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

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
