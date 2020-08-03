<?php

namespace App;

use App\Events\ActivityEvent;
use App\Traits\HasMediaLink;
use App\Traits\HasProjectScopes;
use App\Traits\TaskTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;
use Musonza\Chat\Chat;
use Plank\Metable\Metable;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Project extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait, HasMediaLink, LogsActivity, Metable, HasProjectScopes, Searchable, TaskTrait;

    protected $paginate = 10;

    protected $dates = ['deleted_at'];

    protected static $logName = 'project';

    protected $fillable = [
        'title', 'started_at', 'end_at', 'description', 'status', 'company_id', 'type', 'props', 'service_id'
    ];

    protected static $logAttributes = [
        'title', 'started_at', 'end_at', 'status', 'company_id'
    ];

    protected $casts = [
        'props' => 'array'
    ];

    /**
     * @param Activity $activity
     * @param string $eventName
     */
    public function tapActivity(Activity $activity, string $eventName)
    {
        $description = $this->getDescriptionForEvent($eventName);
        ActivityEvent::dispatch($activity, $description);
    }

    /**
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "A project has been {$eventName}";
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mediaLinks()
    {
        return $this->hasMany(MediaLink::class, 'model_id', 'id')
            ->where('collection_name', 'project.files.links');
    }


    /**
     * @throws \Exception
     */
    public function sendMessages()
    {
        request()->validate([
            'type' => 'required',
            'message' => 'required|string',
            'from_id' => 'required|exists:users,id'
        ]);

        $model = $this->conversations();

        if (request()->type == 'team')
            $model->where('type', 'team');
        else
            $model->where('type', 'client');

        $conversation = $model->first();

        $from = User::findOrFail(request()->from_id);
        return Chat::message(request()->message)->from($from)->to($conversation)->send();
    }

    /**
     * @return mixed
     */
    public function messages()
    {
        $model = $this->conversations();

        if (request()->has('type') && request()->type == 'team')
            $model->where('type', 'team');
        else
            $model->where('type', 'client');

        $convo = $model->first();

        return $convo->messages()
            ->latest()
            ->paginate($this->paginate);
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function projectReports()
    {
        $model = $this->reports();

        if (request()->has('sort') && !empty(request()->sort)) {

            list($sortName, $sortValue) = parseSearchParam(request());

            $model->orderBy($sortName, $sortValue);
        }

        if (request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        $data = $model->paginate($this->paginate);

        if (request()->has('all') && request()->all)
            $data = $model->get();

        return $data;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function importMilestones()
    {
        $template = Template::findOrFail(request()->template_id);

        //get milestones
        if ($template->milestones->count() <= 0)
            return response(['message' => 'Template has no milestones.'], 500);

        foreach ($template->milestones as $key => $milestone) {

            $new_milestone = $milestone->replicate();

            $new_milestone->project_id = $this->id;

            $new_milestone->save();

            if ($milestone->tasks->count() > 0) {

                foreach ($milestone->tasks as $task) {

                    $new_task = $new_milestone->tasks()->create([
                        'title' => $task->title,
                        'description' => $task->description,
                        'status' => $task->status,
                        'days' => $task->days
                    ]);

                    if (!empty($task->role_id)) {

                        $role_id = $task->role_id;

                        $role_user = auth()->user()
                            ->company()
                            ->members()
                            ->join('role_user as ru', function ($join) use ($role_id) {
                                $join->on('ru.user_id', '=', 'users.id')
                                    ->where('ru.role_id', $role_id);
                            })
                            ->first();

                        if (!$this->team->contains($role_user))
                            $this->team()->attach($role_user, ['role' => 'Members']);

                        $new_task->assigned()->attach($role_user->id);
                    }
                }

            }
        }
    }

    /**
     * @param $status
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function taskWhereStatus($status)
    {
        return $this->tasks()
            ->where('tasks.status', $status)
            ->get();
    }

    /**
     * @return Model
     */
    public function storeInvoice()
    {
        request()->validate([
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
            'notes' => request()->notes ?? null,
            'billed_to' => ucfirst($client->last_name) . ', ' . ucfirst($client->first_name),
            'billed_from' => ucfirst(auth()->user()->last_name) . ', ' . ucfirst(auth()->user()->first_name)
        ];

        if (request()->has('billed_to'))
            $data['billed_to'] = request()->billed_to;

        if (request()->has('billed_from'))
            $data['billed_from'] = request()->billed_from;

        if (request()->has('discount'))
            $data['discount'] = request()->discount;

        if (request()->has('tax'))
            $data['tax'] = request()->tax;

        if (request()->has('shipping'))
            $data['shipping'] = request()->shipping;

        if (request()->has('company_logo')) {
            $data['company_logo'] = request()->company_logo;
        }
        return $this->invoices()->create($data);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Scope a query to only include projects of a given type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('projects.type', $type);
    }

    /**
     * @return false|float|int
     */
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

        if ($all != 0)
            $percentage = round(($done / $all) * 100, 2);

        return $percentage;
    }

    /**
     * @return false|string
     */
    public function totalTime()
    {
        $project = $this;

        $tasks = $project->tasks;

        $proj_total_sec = 0;

        if (empty($tasks))
            return gmdate("H:i:s", $proj_total_sec);

        foreach ($tasks as $key => $task) {
            $proj_total_sec = $proj_total_sec + $task->totalSec();
        }

        return gmdate("H:i:s", $proj_total_sec);
    }

    /**
     * @return array
     */
    public function timers()
    {
        $tasks = $this->tasks;

        $tasks->load('timers');

        $client = $this->getClient();

        $arr = [];

        foreach ($tasks as $key => $task) {
            if (count($task->timers)) {
                $task['total_time'] = $task->total_time();
                $task['client'] = $client->getMeta('company_name');
                $task['assignee'] = $task->assigned->first()->first_name . ' ' . $task->assigned->first()->last_name;
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

    /**
     * Get the project link
     * @return string
     */
    public function getLinkAttribute()
    {
        return config('app.frontend_url') . '/dashboard/project/preview/' . $this->id;
    }

    /**
     * Get the project creator
     * @return string
     */
    public function getCreatorAttribute()
    {
        $creator = $this->getMeta('creator', false);
        if ($creator) {
            return User::withTrashed()->where('id', $creator)->first();
        }
        return null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function projectClient()
    {
        return $this->hasOne(ProjectUser::class, 'project_id', 'id')->where('role', 'like', '%client');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projectUsers()
    {
        return $this->hasMany(ProjectUser::class, 'project_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projectFolders()
    {
        return $this->hasMany(ProjectFolder::class, 'project_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function projectManager() //get one manager
    {
        return $this->hasOne(ProjectUser::class, 'project_id', 'id')->where('role', 'like', '%manager');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projectManagers() //get all managers
    {
        return $this->hasMany(ProjectUser::class, 'project_id', 'id')->where('role', 'like', '%manager');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projectMembers()
    {
        return $this->hasMany(ProjectUser::class, 'project_id', 'id')->where('role', 'like', '%members');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projectAllMembers()
    {
        return $this->hasMany(ProjectUser::class, 'project_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * @return array
     */
    public function paginatedProjectTasks()
    {
        $tasks = $this->tasks()
            ->where('tasks.deleted_at', null);

        if (request()->has('sort') && !empty(request()->sort)) {

            list($sortName, $sortValue) = parseSearchParam(request());

            $tasks->orderBy($sortName, $sortValue);
        }

        if (request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        $data = $tasks->paginate($this->paginate);

        if (request()->has('all') && request()->all)
            $data = $tasks->get();

        $data->map(function ($model) {
            $arr = [];
            foreach ($model->assigned()->get() as $key => $value) {
                $arr[] = $value->id;
            }
            $model['assigned_id'] = $arr;
            $model['assigned_ids'] = $arr;

            if (is_object($model->assigned()->first()))
                $model['assignee_ids'] = $model->assigned()->first()->id;
            if (is_null($model->assigned()->first()))
                $model['assignee_url'] = '';
            else
                $model['assignee_url'] = $model->assigned()->first()->image_url;
        });

        $tasks = $data->toArray();
        $tasks['counter'] =  $this->taskCounters(false);

        return $tasks;

    }

    /**
     * @return array
     */
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

        if (request()->has('sort') && !empty(request()->sort)) {
            list($sortName, $sortValue) = parseSearchParam(request());
            $tasks->orderBy($sortName, $sortValue);
        }

        if (request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        $data = $tasks->paginate($this->paginate);

        if (request()->has('all') && request()->all)
            $data = $tasks->get();

        $datus = $data->toArray();
        $datus['counter'] = $this->taskCounters(true);

        return $datus;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function paginatedInvoices()
    {
        $model = $this->invoices()
            ->with('billedFrom', 'billedTo')
            ->where('invoices.deleted_at', null);

        if (request()->has('sort') && !empty(request()->sort)) {
            list($sortName, $sortValue) = parseSearchParam(request());
            $model->orderBy($sortName, $sortValue);
        }

        if (request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        $data = $model->paginate($this->paginate);

        if (request()->has('all') && request()->all)
            $data = $model->get();

        $data->map(function ($model) {
            $model['bill_to'] = $model->billedTo->fullname;
            $model['bill_from'] = $model->billedFrom->fullname;
            $model['items'] = gettype($model->items) == 'string' ? json_decode($model->items, true) : $model->items;
            return $model;
        });

        return $data;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function team()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id');
    }

    public function teamIds()
    {
        return $this->team()->pluck('id')->toArray();
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id')->wherePivot('role', 'Members');
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function paginatedMembers()
    {
        list($sortName, $sortValue) = parseSearchParam(request());

        $model = $this->team();
        $table = 'users';

        if (request()->has('sort') && !empty(request()->sort))
            $model->orderBy($sortName, $sortValue);

        if (request()->has('search')) {
            $keyword = request()->search;
            $model->where(function ($query) use ($keyword, $table) {
                $query->where("{$table}.first_name", "like", "%{$keyword}%");
                $query->where("{$table}.last_name", "like", "%{$keyword}%");
                $query->where("{$table}.email", "like", "%{$keyword}%");
                $query->where("{$table}.telephone", "like", "%{$keyword}%");
                $query->where("{$table}.job_title", "like", "%{$keyword}%");
            });
        }

        if (request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        $data = $model->with('tasks')->paginate($this->paginate);
        if (request()->has('all') && request()->all)
            $data = $model->with('tasks')->get();

        return $data;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function client()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id')->wherePivot('role', 'Client');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMembers()
    {
        return $this->belongsToMany(User::class)->wherePivot('role', 'Members')->get();
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client()->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function manager()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id')->wherePivot('role', 'Manager');
    }

    /**
     * @return mixed
     */
    public function getManager()
    {
        return $this->manager()->first();
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->only('title', 'description');
    }

    /**
     *
     * boot events log activity per events
     *
     */
    public static function boot()
    {
        parent::boot();
        // static::addGlobalScope(new ProjectScope);
    }
}
