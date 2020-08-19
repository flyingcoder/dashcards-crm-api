<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\NewTaskCommentCreated;
use App\Events\NewTaskCreated;
use App\Events\TaskUpdated;
use App\Project;
use App\Repositories\TaskRepository;
use App\Task;
use App\Traits\HasConfigTrait;
use App\User;


class TaskController extends Controller
{
    use HasConfigTrait;
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
        $user = request()->has('for_user') ? User::findOrFail(request()->for_user) : request()->user();
        $tasks = $this->repo->userTasks($user, request()->filter ?? 'all')->toArray();
        $tasks['counter'] = $this->repo->taskCounts($user, true);
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
            $has_end_at = request()->has('end_at') && !is_null(request()->end_at);
            if ($has_end_at)
                request()->validate([ 'end_at' => 'after_or_equal:started_at']);

            $started_at = request()->started_at;
            $end_at = request()->end_at ?? null;
            $days = $has_end_at ? round((strtotime($end_at) - strtotime($started_at)) / (60 * 60 * 24)) : null;
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
            $config = $this->getConfigByKey('email_events', false);
            if ($config && $config->new_task)
                event(new NewTaskCreated($task));
        }
        $task->load('assigned');

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
            $config = $this->getConfigByKey('email_events', false);
            if ($config && $config->task_updated)
                event(new TaskUpdated($task));
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
     * @throws \Exception
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
        $project = $task->project;
        $assigned = $project->members()->where('id', '<>', auth()->user()->id)->pluck('id')->toArray();
        if (!empty($assigned)) {
            company_notification(array(
                'targets' => $assigned,
                'title' => $project->title,
                'message' => auth()->user()->first_name . ' commented on task ' . $task->title,
                'type' => 'task_updated',
                'path' => "/dashboard/$project->type/preview/$project->id/tasks/$task->id",
            ));
        }

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
     * @throws \Exception
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
            company_notification(array(
                'targets' => $task->assigned->pluck('id')->toArray(),
                'title' => 'Project task was set as completed!',
                'message' => $log,
                'type' => 'task_updated',
                'path' => "/dashboard/$project->type/preview/$project->id/tasks/$task->id",
            ));
        }

        return $this->task($id);
    }

    /**
     * @param $id
     * @return \Illuminate\Support\Collection
     * @throws \Exception
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
            if (strtolower($status) == 'urgent') {
                company_notification(array(
                    'targets' => $task->assigned->pluck('id')->toArray(),
                    'title' => 'Project task was set as urgent!',
                    'message' => $log,
                    'type' => 'task_updated',
                    'path' => "/dashboard/$project->type/preview/$project->id/tasks/$task->id"
                ));
            }
            $config = $this->getConfigByKey('email_events', false);
            if ($config && $config->task_updated)
                event(new TaskUpdated($task));
        }

        return $this->task($id);
    }
}
