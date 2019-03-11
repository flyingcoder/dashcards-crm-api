<?php

namespace App;

use Auth;
use DB;
use Chat;
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
    use SoftDeletes, HasMediaTrait, LogsActivity;

    protected $paginate = 10;

    protected $dates = ['deleted_at'];

    protected static $logName = 'system';

    protected $fillable = [
        'title', 'started_at', 'service_id', 'end_at', 'description', 'status', 'company_id'
    ];

    protected static $logAttributes = [
        'title', 'started_at', 'service_id', 'end_at', 'description', 'status', 'company_id'
    ];

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
        return $this->hasMany(Reports::class);
    }

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
        return $this->tasks()->get();
        
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
    /**
     *
     * boot events log activity per events
     *
     */
    
    
    public static function boot()
    {
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
