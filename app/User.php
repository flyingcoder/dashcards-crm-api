<?php

namespace App;

use Auth;
use DB;
use Chat;
use Plank\Metable\Metable;
use Kodeine\Acl\Traits\HasRole;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Laravel\Cashier\Billable;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Media;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\Activitylog\Models\Activity;
use App\Events\ActivityEvent;

class User extends Authenticatable implements HasMediaConversions
{
    use Notifiable, 
        HasRole, 
        Metable, 
        SoftDeletes,
        HasApiTokens,
        HasMediaTrait,
        Billable,
        LogsActivity;

    protected $fillable = [
        'username', 'first_name', 'last_name', 'email', 'telephone', 'job_title', 'password', 'image_url', 'created_by'
    ];

    protected static $logName = 'system';

    protected static $logAttributes = [
         'username', 'first_name', 'last_name', 'email', 'telephone', 'job_title', 'password', 'image_url'
    ];

    public function tapActivity(Activity $activity, string $eventName)
    {
        ActivityEvent::dispatch($activity);
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A user has been {$eventName}";
    }

    protected $default_columns = [
        'email', 'first_name', 'id', 'image_url', 'job_title', 'last_name', 'telephone', 'trial_ends', 'username'
    ];

    protected $paginate = 10;

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['deleted_at', 'trial_ends_at', 'subscription_ends_at'];

    public function userRole()
    {
        return collect($this->getRoles())->first();
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
              ->width(368)
              ->height(232)
              ->sharpen(10);
    }

    public function clientStaffs()
    {
        list($sortName, $sortValue) = parseSearchParam(request());

        $model = $this->children();

        if(request()->has('sort') && !empty(request()->sort))
            $model->orderBy($sortName, $sortValue);
        else
            $model->orderBy('users.created_at', 'DESC');

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        $data = $model->paginate($this->paginate);

        $data->map(function ($user) {
            unset($user['tasks']);
            unset($user['projects']);
            $user['tasks'] = $user->tasks()->count();
            $user['projects'] = $user->projects()->count();
            $roles = $user->roles()->first();
            if(!is_null($roles))
                $user['group_name'] = $roles->id;
        });

        return $data;
    }

    public function notes()
    {
        return $this->belongsToMany(Note::class)->withPivot('is_pinned');
    }

    public function addNotes()
    {
        request()->validate([
            'content' => 'required'
        ]);

        $data = [
            'title' => request()->title,
            'content' => request()->content,
            'remind_date' => request()->remind_date
        ];

        $note = $this->notes()->create($data);

        $note->collaborator = $note->users()->select(
                                        'users.first_name',
                                        'users.last_name',
                                        'users.image_url'
                                   )->get();
        return $note;
    }

    public function getNotes()
    {
        list($sortName, $sortValue) = parseSearchParam(request());

        $model = $this->notes();

        if(request()->has('sort') && !is_null($sortValue)) {
            $query = "note_user.is_pinned DESC, CASE WHEN note_user.is_pinned = 1 THEN notes.id ELSE 0 END, {$sortName} {$sortValue}";

            $model->orderByRaw($query);
        } else {
            $query = "note_user.is_pinned DESC, CASE WHEN note_user.is_pinned = 1 THEN notes.id ELSE 0 END, created_at DESC";

            $model->orderByRaw($query);
        }

        if(request()->has('search') && !empty(request()->search)){
            $keyword = request()->search;

            $model->where(function ($query) use ($keyword) {
                        $query->where('notes.title', 'like', '%' . $keyword . '%');
                        $query->orWhere('notes.content', 'like', '%' . $keyword . '%');
                        $query->orWhere('notes.create_at', 'like', '%' . $keyword . '%');
                      });
        }

        if(request()->has('per_page'))
            $this->paginate = request()->per_page;

        $data = $model->paginate($this->paginate);

        if(request()->has('all') && requet()->all)
            $data = $model->get();

        $data->map(function ($note) {
            $note['collaborators'] = $note->collaborators();
        });

        return $data;
    }

    public function unReadMessages()
    {
        $data = $this->messageNotification()
                    ->where('is_seen', 0)
                    ->get();

        $data->map(function ($model) {
            $model->msg = $model->message()->first();
            $model->sender = $model->msg
                                   ->sender()
                                   ->select('id', 'first_name', 'last_name', 'image_url')
                                   ->first();
            $model->body = $model->msg->body;
            unset($model->msg);
        });

        return $data;
    }

    public function messageNotification()
    {
        return $this->hasMany(MessageNotification::class);
    }

    public function scopeDefaultColumn()
    {
       return $this->select(
                    'id', 
                    'email', 
                    'first_name',
                    'image_url', 
                    'job_title', 
                    'last_name', 
                    'telephone', 
                    'trial_ends_at', 
                    'username')
                ->where('id', $this->id)
                ->first();
    }

    public function CountChats()
    {
        return Chat::conversations()
                   ->for(auth()->user())
                   ->get()
                   ->count();
    }

    public function AauthAcessToken(){
        return $this->hasMany(OauthAccessToken::class);
    }

    public function storeInvoice()
    {   
        request()->validate( [
            'date' => 'date',
            'due_date' => 'required|date',
            'title' => 'required',
            'total_amount' => 'required',
            'items' => 'required|string',
            'type' => 'required'
        ]);

        $data = [
            'type' => request()->type,
            'date' => request()->date,
            'user_id' => auth()->user()->id,
            'due_date' => request()->due_date,
            'title' => request()->title,
            'total_amount' => request()->total_amount,
            'items' => request()->items,
            'terms' => request()->terms,
            'tax' => request()->tax,
        ];

        if(request()->has('project_id'))
            $data['project_id'] = request()->project_id;

        if(request()->has('billed_to'))
            $data['billed_to'] = request()->billed_to;

        if(request()->has('billed_from'))
            $data['billed_from'] = request()->billed_from;

        if(request()->has('discount'))
            $data['discount'] = request()->discount;

        if(request()->hasFile('company_logo')) {
            
            $media = $invoice->addMedia(request()->file('company_logo'))->toMediaCollection('invoice');

            $invoice->company_logo = url($media->getUrl());
        }

        $invoice = $this->invoices()->create($data);

        $items = collect(json_decode($invoice->items));
        unset($invoice->items);
        $invoice->items = $items;
        
        return $invoice;
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
    
    public function acts()
    {
        return $this->belongsToMany('App\Activity', 'activity_user', 'user_id', 'activity_id')
                    ->withPivot('read_at');
    }

    public function unreadActivity()
    {
        return $this->acts()
                    ->whereNull('read_at')
                    ->with('causer')
                    ->get();
    }

    public function CountUnreadActivity()
    {
        return $this->acts()
                    ->whereNull('read_at')
                    ->count();
    }

    public function children()
    {
        return $this->hasMany(User::class, 'created_by', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function activity()
    {
        return $this->morphMany('Spatie\Activitylog\Models\Activity', 'causer');
    }

    public function timers()
    {
        return $this->morphMany(Timer::class, 'causer')
                    ->where('subject_type', 'App\Company');
    }

    public function lastTimer()
    {
        return $this->timers()
                    ->latest()
                    ->first();
    }

    public function forms()
    {
        return $this->hasMany(Form::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class)->withTimestamps();
    }

    public function paginatedTasks()
    {
        $tasks = $this->tasks();

        if(request()->has('all') && request()->all)
            return $tasks->get();

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        return $tasks->paginate($this->paginate);
    }

    public function projectsCount()
    {
        return $this->belongsToMany(Project::class)
                    ->selectRaw('count(projects.id) as projects')
                    ->groupBy('project_id', 'user_id');
    }

    public function getProjectsCountAttribute()
    {
        if ( ! array_key_exists('projectsCount', $this->relations)) $this->load('projectsCount');

        $related = $this->getRelation('projectsCount')->first();

        return ($related) ? $related->aggregate : 0;
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class)->withPivot('role');
    }

    public function userPaginatedProject(Request $request)
    {
        list($sortName, $sortValue) = parseSearchParam($request);

        $projects = $this->projects()
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
                        ->select(
                            DB::raw('CONCAT(CONCAT(UCASE(LEFT(manager.last_name, 1)), SUBSTRING(manager.last_name, 2)), ", ", CONCAT(UCASE(LEFT(manager.first_name, 1)), SUBSTRING(manager.first_name, 2))) AS manager_name'),
                            'client.image_url as client_image_url',
                            DB::raw('CONCAT(CONCAT(UCASE(LEFT(client.last_name, 1)), SUBSTRING(client.last_name, 2)), ", ", CONCAT(UCASE(LEFT(client.first_name, 1)), SUBSTRING(client.first_name, 2))) AS client_name'),
                            'projects.*',
                            'services.name as service_name'
                        )->where('projects.deleted_at', null);

        if($request->has('status'))
            $projects->where('status', $request->status);

        if($request->has('sort') && !empty(request()->sort))
            $projects->orderBy($sortName, $sortValue);
        else
            $projects->latest();

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        return $projects->with('tasks')->paginate($this->paginate);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function company()
    {
        return $this->teams()->first()->company;
    }

    public function milestoneTemplate()
    {
    	return $this->hasMany(MilestoneTemplate::class);
    }

    /*
    public static function boot()
    {

        if(!is_null(Auth::user())) {
            User::created(function ($user) {
                activity(Auth::user()->company())
                   ->performedOn($user)
                   ->causedBy(Auth::user())
                   ->log('Created');
            });

            User::deleted(function ($user) {
                activity(Auth::user()->company())
                   ->performedOn($user)
                   ->causedBy(Auth::user())
                   ->log('Deleted');
            });

            User::saved(function ($user) {
                activity(Auth::user()->company())
                   ->performedOn($user)
                   ->causedBy(Auth::user())
                   ->log('Updated');
            });                 
        }

        User::deleting(function($user) {
            foreach(['services', 'tasks', 'projects'] as $relation)
            {
                foreach($user->{$relation} as $item)
                {
                    $item->delete();
                }
            }
        });
    }*/
}
