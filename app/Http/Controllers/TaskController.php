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
        $company = auth()->user()->company();

        return $company->allCompanyPaginatedTasks();
    }

    public function mine()
    {
        return auth()->user()->paginatedTasks();
    }

    /**
     *
     * store a task from an api call
     *
     */
    public function store(TaskRequest $request)
    {
        //(new TaskPolicy())->create();
        
        return Task::store();
    }

     /**
     *
     * Update a task
     *
     */

    public function update($milestone_id, $task_id, TaskRequest $request)
    {
        $task = Task::findOrFail($task_id);

        //(new TaskPolicy())->update($task);

        return $task->updateTask();
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
     * Delete a specific task
     *
     */

    public function delete($id)
    {
        $task = Task::findOrFail($id);

        // (new TaskPolicy())->delete($task);

        if($task->delete()) {
            return response('Task is successfully deleted.', 200);
        } else {
            return response('Failed to delete task.', 500);
        }
    }
    
    

    /**
     *
     * get a single task
     *
     */
    public function task($id)
    {
        $task = Task::findOrFail($id);

        $task->assigned;

        $task->comments;

        $total_time = $task->total_time();

        $task = collect(json_decode($task->toJson()));

        $task->put('total_time', $total_time);

        return $task;
        // (new TaskPolicy())->view($task);

    }

}
