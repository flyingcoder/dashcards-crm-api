<?php

namespace App;

use Auth;
use DB;
use Plank\Metable\Metable;
use Kodeine\Acl\Traits\HasRole;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Laravel\Cashier\Billable;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class User extends Authenticatable
{
    use Notifiable, 
        HasRole, 
        Metable, 
        SoftDeletes,
        HasApiTokens,
        Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'first_name', 'last_name', 'email', 'telephone', 'job_title', 'password', 'image_url'
    ];

     protected static $logAttributes = [
         'username', 'first_name', 'last_name', 'email', 'telephone', 'job_title', 'password', 'image_url'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['deleted_at'];

    /**
     *
     * Recursive relationship
     *
     */

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

    /**
     *
     * Forms relationship
     *
     */
    public function forms()
    {
        return $this->hasMany(Form::class);
    }

    /**
     *
     * user relationships
     *
     */

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class)->withTimestamps();
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

        if($request->has('sort'))
            $projects->orderBy($sortName, $sortValue);

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
