<?php

namespace App\Http\Controllers;

use App\Task;
use App\Comment;
use App\Project;
use App\Policies\TaskPolicy;
use App\Events\NewTaskCommentCreated;
use App\Http\Requests\TaskRequest;


class TaskController extends Controller
{

    public function index()
    {
        //(new TaskPolicy())->index();
        $user = \App\User::first();

        $company = $user->company();

        //dd($company->allCompanyPaginatedTasks(request()));
        return $company->allCompanyPaginatedTasks(request());
    }

    public function comments($id)
    {
        $task = Task::findOrFail($id);

        return $task->comments->load(['causer']);
    }

    public function addComments($id)
    {
        $task = Task::findOrFail($id);

        request()->validate([
            'body' => 'required'
        ]);

        $comment = new Comment([ 
            'body' => request()->body,
            'causer_id' => auth()->user()->id,
            'causer_type' => 'App\User'
        ]);

        $new_comment = $task->comments()->save($comment);

        NewTaskCommentCreated::dispatch($task, $new_comment);

        return $new_comment;

    }
    
    //currently not in use. This is for in house view.
    public function save($project_id)
    {
        (new TaskPolicy())->create();

    	return view('pages.project-hq.tasks-new', ['project_id' => $project_id]);
    }

    public function stats($id)
    {
        //(new TaskPolicy())->index();
        $project = Project::findOrFail($id);

        $all = $project->tasks->count();

        $urgent = $project->tasks()->where('tasks.status', 'urgent')->count();

        $open = $project->tasks()->where('tasks.status', 'open')->count();

        $closed = $project->tasks()->where('tasks.status', 'closed')->count();

        $invalid = $project->tasks()->where('tasks.status', 'invalid')->count();

        $stat = collect([
            'all' => $all,
            'urgent' => $urgent,
            'open' => $open,
            'closed' => $closed,
            'invalid' => $invalid
        ]);

        return $stat;
    }

    /**
     *
     * store a task from an api call
     *
     */
    public function store(TaskRequest $request)
    {
        //test user capacity to create task
        (new TaskPolicy())->create();

        //validate and store request to database
        
        $task = Task::store($request);

        //return response back to json
        return response()->json(['created' => $task[0], 'task' => $task[1]]);
    }

    /**
     *
     * Delete a specific task
     *
     */

    public function delete($id)
    {
        $task = Task::findOrFail($id);

        // (new TaskPolicy())->delete($task);

        Task::destroy($id);

        return response()->json(['deleted' => $task]); 
    }


    /**
     *
     * Update a task
     *
     */

    public function update($id)
    {
        $task = Task::findOrFail($id);

        (new TaskPolicy())->update($task);

        /*$validated = request()->validate([
            'title' => 'required',
            'description' => 'required',
            'milestone_id' => 'integer|exists:milestones,id',
            'started_at' => 'date',
            'end_at' => 'date',
            'days' => 'integer'
        ]);*/

        $task->title = request()->title;
        $task->description = request()->description;

        if(request()->has('days'))
            $task->days = request()->days;

        if(request()->has('started_at'))
            $task->started_at = request()->started_at;

        if(request()->has('end_at'))
            $task->end_at = request()->end_at;

        $task->save();

        return response()->json(['updated' => $task]);
    }
    
    

    /**
     *
     * get a single task
     *
     */
    public function task($id)
    {
        $task = Task::where('id', $id)
                    ->where('deleted_at', null)
                    ->first();
        return $task->load('assigned');

        // if(empty($task)){
        //     abort(403, 'Task not found!');
        // }

        // (new TaskPolicy())->view($task);

    }

}
