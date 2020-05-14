<?php

namespace App\Http\Controllers;

use App\Repositories\TimerRepository;
use App\Task;
use App\Timer;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TimerController extends Controller
{
    protected $paginate = 10;

    protected $repo;

    public function __construct(TimerRepository $repo)
    {
        $this->repo = $repo;
        if (request()->has('per_page') && request()->per_page > 0) {
            $this->paginate = request()->per_page;
        }
    }

	public function index()
	{
		return auth()->user()
					 ->company()
					 ->allTimers();
	}

	public function task()
	{
		return Timer::where('subject_type', 'App\Task')
						->where('subject_id', request()->id)
						->get();
	}

    public function timer($action)
    {
        $timer = new Timer();

        return $timer->trigger($action);
    }

    public function status($user_id = null)
    {
    	$user = auth()->user();

    	if (!is_null($user_id)) {
    		$user = User::findOrFail($user_id);
    	}

    	return $user->timers()->latest()->first();
    }

    public function taskTimers()
    {
        $user = auth()->user();

        if (request()->has('all')) {
            $tasks = $user->company()->tasks();
        } else {
            $tasks = $user->tasks();
        }
        if (request()->has('filter') && strtolower(request()->filter) != 'all') {
            $tasks = $tasks->whereRaw('LOWER(status) = ?', [strtolower(request()->filter)]);
        }   

        $tasks = $tasks->select('tasks.*')
                    ->with('assigned')
                    ->orderBy('tasks.status', 'DESC')
                    ->orderBy('tasks.id', 'ASC')
                    ->paginate($this->paginate);

        $tasksItems = $tasks->getCollection();
        $data = collect([]);

        foreach ($tasksItems as $key => $task) {
            $timer = $this->repo->getTimerForTask($task);
            // $service = $task->milestone->project->service->name ?? '';
            $project = $task->milestone->project;
            $client = $task->project()->getClient();
            $data->push(array_merge($task->toArray(), ['timer' => $timer, 'project' => $project, 'client' => $client ]));   
        }

        $tasks->setCollection($data);

        return $tasks;
    }

    public function globalTimers()
    {
        $user = auth()->user();
        
        $clientTeam = $user->company()->clientTeam()->id ?? 0;

        $members = $user->company()->members()
            ->where('teams.id', '<>', $clientTeam)
            ->select('users.*')
            ->paginate($this->paginate);

        $membersItems = $members->getCollection();
        $data = collect([]);

        $date = request()->has('date') ? request()->date : now()->format('Y-m-d');

        foreach ($membersItems as $key => $user) {
            $timer = $this->repo->getTimerForUserByDate($user,  $date);
            $data->push(array_merge($user->toArray(), ['timer' => $timer ]));   
        }

        $members->setCollection($data);

        return $members;
    }

    public function forceStopTimer($type)
    {
        if ($type == 'global') {
            request()->validate(['user_id' => 'required|exists:users,id']);
        } else {
            request()->validate([
                'user_id'  => 'required|exists:users,id',
                'task_id'  => 'required|exists:tasks,id'
            ]);
            $task = Task::findOrFail(request()->task_id);
        }

        $user = User::findOrFail(request()->user_id);

    }
}
