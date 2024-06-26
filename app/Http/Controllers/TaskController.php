<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\NewTaskCommentCreated;
use App\Events\ProjectTaskNotification;
use App\Http\Requests\TaskRequest;
use App\Policies\TaskPolicy;
use App\Project;
use App\Task;


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

    public function update($task_id)
    {
        $task = Task::findOrFail($task_id);

        //(new TaskPolicy())->update($task);

        return $task->updateTask();
    }

    public function updateTask($milestone_id, $task_id)
    {
         $task = Task::findOrFail($task_id);

        //(new TaskPolicy())->update($task);

        return $task->updateTask();
    }

    public function delete($task_id)
    {
        $task = Task::findOrFail($task_id);

        // (new TaskPolicy())->delete($task);

        return $task->destroy($task_id);
    }

    public function deleteTask($milestone_id, $task_id)
    {
        $task = Task::findOrFail($task_id);

        // (new TaskPolicy())->delete($task);

        return $task->destroy($task_id);
    }

    public function bulkDeleteTask($milestone_id)
    {
        request()->validate([
            'ids' => 'required|array'
        ]);
        // (new TaskPolicy())->delete($task);

        $tasks = Task::whereIn('id', request()->ids)->get();

         if (!$tasks->isEmpty()) {
            foreach ($tasks as $key => $task) {
                $task->delete();
            }
        }

        return response()->json(['message' => $tasks->count().' task(s)  successfully deleted'], 200);
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
        $new_comment->load('causer');

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
     * get a single task
     *
     */
    public function task($id)
    {
        $task = Task::findOrFail($id);

        $status     = $task->timerStatus();
        $total_time = $task->total_time();
        $comments   = $task->comments->load('causer');

        $assigned     = $task->assigned()->get();
        $assignee_url = $assigned->isEmpty() ? '' : $assigned->first()->image_url;
        $assigned_ids = $assigned->isEmpty() ? [] : $assigned->pluck('id')->toArray();

        $task = collect($task);

        $task->put('total_time', $total_time);
        $task->put('timer_status', $status);
        $task->put('assignee_url', $assignee_url);
        $task->put('comments', $comments);
        $task->put('assigned_ids', $assigned_ids);
        $task->put('assigned_id', $assigned_ids);

        return $task;
    }

    public function markAsComplete($id)
    {
        request()->validate([
            'status' => 'required|string'
        ]);

        $task = Task::findOrFail($id);
        $task->markStatus(request()->status);
        $user = auth()->user();

        $project =  $task->project();
        $log = $user->first_name.' marked as completed the task '.$task->title;
        $activity = activity('system.task')
                  ->performedOn($project ?? $task)
                  ->causedBy($user)
                  ->log($log);

        if (request()->has('notify_complete') && request()->notify_complete) {
            $data = array(
                    'title' => 'Project Task updated!',
                    'message' => $log,
                    'receivers' => $project ? $project->members()->pluck('id')->toArray() : [],
                    'project' => $project
                );
            broadcast(new ProjectTaskNotification($user->company()->id, $data));
        }

        return $this->task($id);
    }
}
