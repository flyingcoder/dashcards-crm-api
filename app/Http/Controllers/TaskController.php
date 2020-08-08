<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\NewTaskCommentCreated;
use App\Events\ProjectTaskNotification;
use App\Events\TaskUpdated;
use App\Project;
use App\Repositories\TaskRepository;
use App\Task;


class TaskController extends Controller
{
    /**
     * @var TaskRepository
     */
    protected $repo;

    /**
     * TaskController constructor.
     * @param TaskRepository $repo
     */
    public function __construct(TaskRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        if (auth()->user()->hasRoleLike('client')) {
            //todo return only projects tasks that the client associated with
            return $this->mine();
        }
        $company = auth()->user()->company();
        return $company->allCompanyPaginatedTasks();
    }

    /**
     * @return mixed
     */
    public function mine()
    {
        $tasks = $this->repo->userTasks(request()->user(), request()->filter ?? 'all')->toArray();
        $tasks['counter'] = $this->repo->taskCounts(request()->user(), true);
        return $tasks;
    }


    /**
     * @return mixed
     */
    public function store()
    {
        //(new TaskPolicy())->create();
        request()->validate([
            'title' => 'required|string'
        ]);

        if (request()->started_at != null) {
            request()->validate([
                'end_at' => 'after_or_equal:started_at',
            ]);
            $started_at = request()->started_at;
            $end_at = request()->end_at;
            $days = round((strtotime($end_at) - strtotime($started_at)) / (60 * 60 * 24));
        } else {
            $started_at = date("Y-m-d", strtotime("now"));
            $end_at = date("Y-m-d", strtotime(request()->days . ' days'));
            $days = request()->days;
        }

        $task = Task::create([
            'title' => request()->title,
            'description' => request()->description ?? null,
            'milestone_id' => request()->milestone_id ?? null,
            'project_id' => request()->project_id ?? null,
            'started_at' => $started_at,
            'end_at' => $end_at,
            'status' => 'Open',
            'days' => $days
        ]);

        $task->setMeta('creator', auth()->user()->id);

        if (request()->has('assigned')) {
            $task->assigned()->sync(request()->assigned);
            $task->assigned_ids = request()->assigned;
        }

        if ($task->project_id > 0) {
            //todo :kirby add handler or convert to job
            //event(new NewTaskCreated($task));
        }

        return $task;
    }

    /**
     * @param $task_id
     * @return mixed
     */
    public function update($task_id)
    {
        $task = Task::findOrFail($task_id);
        //(new TaskPolicy())->update($task);
        $task->updateTask();

        $task = $task->fresh();
        if ($task->project_id > 0) {
            //todo :kirby add handler or convert to job
            //event(new TaskUpdated($task));
        }

        return $task->toArray();
    }

    /**
     * @param $milestone_id
     * @param $task_id
     * @return mixed
     */
    public function updateTask($milestone_id, $task_id)
    {
        $task = Task::findOrFail($task_id);
        //(new TaskPolicy())->update($task);

        $task->updateTask();

        $task = $task->fresh();

        if ($task->project_id > 0) {
            event(new TaskUpdated($task));
        }

        return $task;
    }

    /**
     * @param $task_id
     * @return mixed
     */
    public function delete($task_id)
    {
        $task = Task::findOrFail($task_id);

        // (new TaskPolicy())->delete($task);
        $task->delete();

        return $task;
    }

    /**
     * @param $milestone_id
     * @param $task_id
     * @return mixed
     */
    public function deleteTask($milestone_id, $task_id)
    {
        $task = Task::findOrFail($task_id);

        // (new TaskPolicy())->delete($task);

        return $task->destroy($task_id);
    }

    /**
     * @param $milestone_id
     * @return \Illuminate\Http\JsonResponse
     */
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

        return response()->json(['message' => $tasks->count() . ' task(s)  successfully deleted'], 200);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function comments($id)
    {
        $task = Task::findOrFail($id);

        return $task->comments->load(['causer']);
    }

    /**
     * @param $id
     * @return mixed
     */
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

    /**
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function stats($id)
    {
        //(new TaskPolicy())->index();
        $project = Project::findOrFail($id);

        $all = $project->tasks->count();
        $urgent = $project->tasks()->where('tasks.status', 'urgent')->count();
        $open = $project->tasks()->where('tasks.status', 'open')->count();
        $pending = $project->tasks()->where('tasks.status', 'pending')->count();
        $completed = $project->tasks()->where('tasks.status', 'completed')->count();
        $behind = $project->tasks()->where('tasks.status', 'behind')->count();

        return collect([
            'all' => $all,
            'urgent' => $urgent,
            'open' => $open,
            'completed' => $completed,
            'pending' => $pending,
            'behind' => $behind
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function task($id)
    {
        $task = Task::findOrFail($id);

        $status = $task->timerStatus();
        $total_time = $task->total_time();
        $comments = $task->comments->load('causer');

        $assigned = $task->assigned;
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

    /**
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function markAsComplete($id)
    {
        request()->validate([
            'status' => 'required|string'
        ]);

        $task = Task::findOrFail($id);
        $task->markStatus(request()->status);
        $user = auth()->user();

        $project = $task->project;
        $log = $user->first_name . ' marked as completed the task ' . $task->title;
        activity('system.task')
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

    /**
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function markAsUrgent($id)
    {
        request()->validate([
            'status' => 'required|string'
        ]);

        $task = Task::findOrFail($id);
        $status = strtolower($task->status) == 'urgent' ? 'Open' : 'Urgent';
        $task->status = $status;
        $task->save();

        $user = auth()->user();
        $project = $task->project;
        $log = $user->first_name . ' marked as ' . $status . ' the task ' . $task->title;
        activity('system.task')->performedOn($project ?? $task)->causedBy($user)->log($log);


        if (!$task->assigned->isEmpty()) {
            if ($status == 'Urgent') {
                $data = array(
                    'title' => 'Project task was set as urgent!',
                    'message' => $log,
                    'receivers' => $task->assigned->pluck('id')->toArray(),
                    'project' => $project
                );
                broadcast(new ProjectTaskNotification($user->company()->id, $data));
            }
            //todo :kirby add handler or convert to job
            //event(new TaskUpdated($task));
        }

        return $this->task($id);
    }
}
