<?php

namespace App;

use App\Events\ActivityEvent;
use Auth;
use Carbon\Carbon;
use Chat;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Plank\Metable\Metable;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\MediaLibrary\Media;

class Project extends Model implements HasMediaConversions
{
    use SoftDeletes, HasMediaTrait, LogsActivity, Metable;

    protected $paginate = 10;

    protected $dates = ['deleted_at'];

    protected static $logName = 'project';

    protected $fillable = [
        'title', 'started_at', 'service_id', 'end_at', 'description', 'status', 'company_id'
    ];

    protected static $logAttributes = [
        'title', 'started_at', 'service_id', 'end_at', 'status', 'company_id'
    ];

    public function tapActivity(Activity $activity, string $eventName)
    {
        $description = $this->getDescriptionForEvent($eventName);
        ActivityEvent::dispatch($activity, $description);
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A project has been {$eventName}";
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    public function sendMessages()
    {
        request()->validate([
            'type' => 'required',
            'message' => 'required|string',
            'from_id' => 'required|exists:users,id'
        ]);

        $model = $this->conversations();

        if(request()->type == 'team')
            $model->where('type', 'team');
        else
            $model->where('type', 'client');

        $convo = $model->first();

        $from = User::findOrFail(request()->from_id);

        return Chat::message(request()->message)
                   ->from($from)
                   ->to($convo)
                   ->send();
    }

    public function messages()
    {
        $model = $this->conversations();

        if(request()->has('type') && request()->type == 'team')
            $model->where('type', 'team');
        else
            $model->where('type', 'client');

        $convo = $model->first();

        return $convo->messages()
                     ->paginate($this->paginate);
    }

    public function projectReports()
    {
        $model = $this->reports();

        if(request()->has('sort') && !empty(request()->sort)) {

            list($sortName, $sortValue) = parseSearchParam(request());

            $model->orderBy($sortName, $sortValue);
        }

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        $data = $model->paginate($this->paginate);

        if(request()->has('all') && request()->all)
            $data = $model->get();

        return $data;
    }

    public function createReports()
    {   
        request()->validate([
            'title' => 'required',
            'url' => 'required'
        ]);

        return $this->reports()->create([
            'company_id' => auth()->user()->company()->id,
            'title' => request()->title,
            'description' => request()->description,
            'url' => request()->url
        ]);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
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

                   if(!empty($task->role_id)) {

                        $role_id = $task->role_id;

                        $role_user = auth()->user()
                                          ->company()
                                          ->members()
                                          ->join('role_user as ru', function($join) use ($role_id) {
                                                        $join->on('ru.user_id', '=', 'users.id')
                                                             ->where('ru.role_id', $role_id);
                                                   })
                                          ->first();

                        if(!$this->members->contains($role_user))
                            $this->members()->attach($role_user, ['role' => 'Members']);

                        $new_task->assigned()->attach($role_user->id);

                   }

                   
                }

            }
        }
    }

    public function taskWhereStatus($status)
    {
        return $this->tasks()
                     ->where('tasks.status', $status)
                     ->get();
    }

    public function storeInvoice()
    {
        request()->validate( [
            'date' => 'date',
            'due_date' => 'required|date',
            'title' => 'required',
            'total_amount' => 'required',
            'items' => 'required'
        ]);

        $client = $this->getClient();

        $data = [
            'date' => request()->date,
            'user_id' => auth()->user()->id,
            'due_date' => request()->due_date,
            'title' => request()->title,
            'total_amount' => request()->total_amount,
            'items' => collect(request()->items),
            'terms' => request()->terms,
            'tax' => request()->tax,
            'billed_to' => ucfirst($client->last_name) . ', ' . ucfirst($client->first_name),
            'billed_from' => ucfirst(auth()->user()->last_name) . ', ' . ucfirst(auth()->user()->first_name)
        ];

        if(request()->has('billed_to'))
            $data['billed_to'] = request()->billed_to;

        if(request()->has('billed_from'))
            $data['billed_from'] = request()->billed_from;

        if(request()->has('discount'))
            $data['discount'] = request()->discount;

        $invoice = $this->invoices()->create($data);

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

    public function timers()
    {
        $tasks = $this->tasks;

        $tasks->load('timers');

        $client = $this->getClient();

        $arr = [];

        foreach ($tasks as $key => $task) {
            if(count($task->timers)) {
                $task['total_time'] = $task->total_time();
                $task['client'] = $client->getMeta('company_name');
                $task['assignee'] = $task->assigned->first()->first_name.' '.$task->assigned->first()->last_name;
                $arr[] = $task;
            }
        }

        return $arr;
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

    public function paginatedProjectTasks()
    {
        $tasks = $this->tasks()
                      ->where('tasks.deleted_at', null);

        if( request()->has('sort') && !empty(request()->sort) ) {

            list($sortName, $sortValue) = parseSearchParam(request());

            $tasks->orderBy($sortName, $sortValue);
        }

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        $data = $tasks->paginate($this->paginate);

        if(request()->has('all') && request()->all)
            $data = $tasks->get();
        
        $data->map(function ($model) {
            $arr = [];
            foreach ($model->assigned()->get() as $key => $value) {
                $arr[] = $value->id;
            }
            $model['assigned_id'] = $arr;
            $model['assigned_ids'] = $arr;

            if(is_object($model->assigned()->first()))
                $model['assignee_ids'] = $model->assigned()->first()->id;
                if(is_null($model->assigned()->first()))
                    $model['assignee_url'] = '';
                else
                    $model['assignee_url'] = $model->assigned()->first()->image_url;
        });

        $datus = $data->toArray();

        $datus['counter'] = [
            'open' => $this->taskStatusCounter('open'),
            'behind' => $this->taskStatusCounter('behind'),
            'completed' => $this->taskStatusCounter('completed'),
            'pending' => $this->taskStatusCounter('pending')
        ];

        return $datus;
        
    }

    public function taskStatusCounter($status)
    {
        return $this->tasks()->where('tasks.status', $status)->count();
    }

    public function paginatedProjectMyTasks()
    {
         $tasks = $this->tasks()
                      ->join('task_user as tu', 'tu.task_id', '=', 'tasks.id')
                      ->join('users', 'users.id', '=', 'tu.user_id')
                      ->where('users.id', auth()->user()->id)
                      ->select(
                        'users.image_url as image',
                       DB::raw('CONCAT(CONCAT(UCASE(LEFT(users.last_name, 1)), SUBSTRING(users.last_name, 2)), ", ", CONCAT(UCASE(LEFT(users.first_name, 1)), SUBSTRING(users.first_name, 2))) AS assignee'),
                       'tasks.*')
                      ->where('tasks.deleted_at', null);

        if(request()->has('sort') && !empty(request()->sort)) {

            list($sortName, $sortValue) = parseSearchParam(request());

            $tasks->orderBy($sortName, $sortValue);
        }

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        $data = $tasks->paginate($this->paginate);

        if(request()->has('all') && request()->all)
            $data = $tasks->get();

        $datus = $data->toArray();

        $datus['counter'] = [
            'open' => $this->taskStatusCounter('open'),
            'behind' => $this->taskStatusCounter('behind'),
            'completed' => $this->taskStatusCounter('completed'),
            'pending' => $this->taskStatusCounter('pending')
        ];

        return $datus;
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

        $model = $this->members()
                      ->select(
                        'users.*',
                        DB::raw('CONCAT(CONCAT(UCASE(LEFT(users.last_name, 1)), SUBSTRING(users.last_name, 2)), ", ", CONCAT(UCASE(LEFT(users.first_name, 1)), SUBSTRING(users.first_name, 2))) AS name')
                      );

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


        $data = $model->with('tasks')->paginate($this->paginate);

        if(request()->has('all') && request()->all)
            $data = $model->with('tasks')->get();

        return $data;
    }

    public function client()
    {
        return $this->belongsToMany(User::class)
                    ->wherePivot('role', 'Client');
    }

    public function getMembers()
    {
        return $this->belongsToMany(User::class)
                    ->wherePivot('role', 'Members')
                    ->get();
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
    /**
     *
     * boot events log activity per events
     *
     */
    
    
    public static function boot()
    {
        parent::boot();
        
        Project::created(function ($project) {

            $participants = collect($project->members()
                                    ->select('id')
                                    ->get());

            $participants->flatten();

            $client_convo = Chat::createConversation($participants->all());

            $client_convo->project_id = $project->id;

            $client_convo->type = 'client';

            $client_convo->save();

            $team_convo = Chat::createConversation($participants->all());

            $team_convo->project_id = $project->id;

            $team_convo->type = 'team';

            $team_convo->save();

        });

        if(!is_null(Auth::user())) {
            /*Project::created(function ($project) {
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
            });*/
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
