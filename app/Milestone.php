<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Http\Request;


class Milestone extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'project_id', 'started_at', 'end_at', 'days', 'status'
    ];

    protected static $logAttributes = [
        'title', 'project_id', 'started_at', 'end_at', 'days', 'status'
    ];

    protected $rules = [
        'title' => 'required',
        'started_at' => 'required|date',
        'end_at' => 'required|date'
    ];

    protected $dates = ['deleted_at'];

    public function store($request, Project $project)
    {
        if($request->started_at != null){
            $started_at = $request->started_at;
            $end_at = $request->end_at;
        } else {
            $started_at = date("Y-m-d",strtotime("now"));
            $end_at = date("Y-m-d",strtotime($request->days . ' days'));
        }
        
        $milestone = self::create([
            'project_id' => $project->id,
            'title' => $request->title,
            'started_at' => $started_at,
            'end_at' => $end_at,
            'status' => 'In Progress'
        ]);

        return $milestone;
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
