<?php

namespace App;

use Auth;
use Plank\Metable\Metable;
use Kodeine\Acl\Traits\HasRole;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Laravel\Cashier\Billable;
use Spatie\Activitylog\Models\Activity;

class User extends Authenticatable
{
    use Notifiable, 
        HasRole, 
        Metable, 
        SoftDeletes,
        HasApiTokens, 
        LogsActivity,
        Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'telephone', 'job_title', 'password', 'image_url'
    ];

     protected static $logAttributes = [
        'name', 'email', 'telephone', 'job_title', 'password', 'image_url'
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
        return $this->belongsToMany(Task::class);
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

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function company()
    {
        return $this->teams()->first()->company;
    }

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
    }
}
