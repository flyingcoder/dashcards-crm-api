<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Http\Request;
use Task;

class Milestone extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'project_id', 'started_at', 'end_at', 'days', 'status', 'percentage'
    ];

    protected static $logAttributes = [
        'title', 'project_id', 'started_at', 'end_at', 'days', 'status', 'percentage'
    ];

    protected $rules = [
        'title' => 'required',
        'started_at' => 'required|date',
        'end_at' => 'required|date',
        'percentage' => 'required',
    ];

    protected $dates = ['deleted_at'];

    public function store(Request $request, Project $project)
    {
        $request->validate($this->rules);
        
        $milestone = self::create([
            'project_id' => $project->id,
            'title' => $request->title,
            'started_at' => $request->started_at,
            'end_at' => $request->end_at,
            'percentage' => $request->percentage,
            'status' => 'In Progress'
        ]);

        return $milestone;
    }

    public function importTemplate($template, Project $project){
        $template = $request->all();
        $milestones = $template['milestone'];
        foreach($milestones as $milestone){
            $m = self::create([
                'project_id' => $project->id,
                'title' => $milestone->title,
                'started_at' => strtotime(),
                'end_at' => strtotime($milestone->days + ' days'),
                'percentage' => $request->percentage,
                'status' => 'In Progress'
            ]);
            foreach($milestone['tasks'] as $task){
                Task::create([
                    'title' => $task['title'],
                    'description' => $task['description'],
                    'started_at' => strtotime(),
                    'end_at' => strtotime($task['days'] + ' days'),
                    'milestone_id' => $m->id
                ]);
            }
        }
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
