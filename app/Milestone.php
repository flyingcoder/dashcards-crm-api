<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Http\Request;
use Spatie\Activitylog\Contracts\Activity;
use App\Events\ActivityEvent;

class Milestone extends Model
{
    use SoftDeletes, LogsActivity;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'project_id', 'started_at', 'end_at', 'days', 'status'
    ];

    protected static $logName = 'system';

    protected static $logAttributes = [
        'title', 'project_id', 'started_at', 'end_at', 'days', 'status'
    ];

    protected $rules = [
        'title' => 'required',
        'started_at' => 'required|date',
        'end_at' => 'required|date'
    ];

    public function tapActivity(Activity $activity, string $eventName)
    {
        ActivityEvent::dispatch($activity);
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A milestone has been {$eventName}";
    }

    protected $dates = ['deleted_at'];

    protected $paginate = 10;

    /*
     * Relationship methods
     */

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /*
     * Action Methods
     */

    public function store($parent, $parent_id)
    {

        $data = [
            'project_id' => 0,
            'title' => request()->title,
            'status' => request()->status
        ];

        if($parent == 'project')
            $data['project_id'] = (int) $parent_id;

        if(request()->has('days'))
            $data['days'] = request()->days;

        if(request()->has('started_at'))
            $data['started_at'] = request()->started_at;

        if(request()->has('end_at'))
            $data['end_at'] = request()->end_at;

        $milestone = self::create($data);

        if($parent == 'template'){

            $model = Template::findOrFail($parent_id);

            $model->milestones()->attach($milestone);
        }

        $milestone['tasks'] = [];

        return $milestone;

    }

    public function addTask()
    {
        request()->validate([
            'title' => 'required',
            'status' => 'required'
        ]);

        $data = [
            'title' => request()->title,
            'description' => request()->description,
            'status' => request()->status
        ];

        if(request()->has('days'))
            $data['days'] = request()->days;

        if(request()->has('started_at'))
            $data['started_at'] = request()->started_at;

        if(request()->has('end_at'))
            $data['end_at'] = request()->end_at;

        if(request()->has('role_id'))
            $data['role_id'] = request()->role_id;

        $task = $this->tasks()->create($data);

        if(request()->has('assigned_id')) 
            $task->assigned()->sync(request()->assigned_id);

        $task->assigned_id = request()->assigned_id;

        return $task;
    }

    public function getTasks()
    {
        $model = $this->tasks();

        if(request()->has('all') && request()->all == true)
            return $model;

        list($sortName, $sortValue) = parseSearchParam(request());

        if(request()->has('sort'))
            $model->orderBy($sortName, $sortValue);

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        return $model->paginate($this->paginate);
    }

    public function updateMilestone()
    {

        $this->title = request()->title;

        $this->status = request()->status;

        if(request()->has('days'))
            $this->days = request()->days;

        if(request()->has('started_at'))
            $this->started_at = request()->started_at;

        if(request()->has('end_at'))
            $this->end_at = request()->end_at;

        $this->save();

        $this->tasks;

        return $this;
    }


    public function paginated($parent, $id)
    {
        list($sortName, $sortValue) = parseSearchParam(request());

        $parent = "App\\".ucfirst($parent);

        $parent_model = $parent::findOrFail($id);

        $model = $parent_model->milestones();

        $model->with(['tasks']);

        if(request()->has('sort'))
            $model->orderBy($sortName, $sortValue);

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        $data = $model->paginate($this->paginate);

        if(request()->has('all') && request()->all == true)
            $data = $model->get();

        $data->map(function ($milestone) {
            $milestone->tasks->map(function ($task) {
                $id = [];
                foreach ($task->assigned()->get() as $value) {
                    $id[] = $value->id;
                }
                $task['assigned_id'] = $id;
            });
        });

        return $data;
    }

    /*
    public static function boot()
    {

        if(!is_null(Auth::user())) {
            Milestone::created(function ($milestone) {
                activity(Auth::user()->company())
                   ->performedOn($milestone)
                   ->causedBy(Auth::user())
                   ->log('Created');
            });

            Milestone::deleted(function ($milestone) {
                activity(Auth::user()->company())
                   ->performedOn($milestone)
                   ->causedBy(Auth::user())
                   ->log('Deleted');
            });

            Milestone::saved(function ($milestone) {
                activity(Auth::user()->company())
                   ->performedOn($milestone)
                   ->causedBy(Auth::user())
                   ->log('Updated');
            });
        }

        Milestone::deleting(function($milestone) {
            foreach(['tasks'] as $relation)
            {
                foreach($milestone->{$relation} as $item)
                {
                    $item->delete();
                }
            }
        });
    }*/
}
