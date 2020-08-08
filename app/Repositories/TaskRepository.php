<?php


namespace App\Repositories;


use App\Project;
use App\Traits\TaskTrait;
use App\User;

/**
 * Class TaskRepository
 * @package App\Repositories
 */
class TaskRepository
{
    use TaskTrait;
    /**
     * @var int|mixed
     */
    protected $paginate = 25;


    /**
     * TaskRepository constructor.
     */
    public function __construct()
    {
        $this->paginate = request()->has('per_page') ? request()->per_page : 25;
    }

    /**
     * @param $source
     * @param bool $self
     * @return array
     */
    public function taskCounts($source, $self = false)
    {
        return $this->taskCounters($self, $source);
    }

    /**
     * @param User $user
     * @param string $filter
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function userTasks(User $user, $filter = 'all')
    {
        $tasks = $user->tasks();

        if ($filter != 'all') {
            $tasks->whereRaw("UPPER(status) = '" . strtoupper($filter) . "'");
        }

        if (request()->has('sort') && !empty(request()->sort)) {
            list($sortName, $sortValue) = parseSearchParam(request());
            $tasks->orderBy($sortName, $sortValue);
        } else {
            $tasks->orderBy('status', 'desc')->orderBy('id', 'asc');
        }

        return $tasks->paginate(request()->per_page ?? $this->paginate);
    }

    /**
     * @param Project $project
     * @param string $filter
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function projectTasks(Project $project, $filter = 'all')
    {
        $tasks = $project->tasks();

        if ($filter != 'all') {
            $tasks->whereRaw("UPPER(status) = '" . strtoupper($filter) . "'");
        }

        if (request()->has('sort') && !empty(request()->sort)) {
            list($sortName, $sortValue) = parseSearchParam(request());
            $tasks->orderBy($sortName, $sortValue);
        } else {
            $tasks->orderBy('status', 'desc')->orderBy('id', 'asc');
        }

        return $tasks->paginate(request()->per_page ?? $this->paginate);
    }

    /**
     * @param Project $project
     * @param User $user
     * @param string $filter
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function userProjectTasks(Project $project, User $user, $filter = 'all')
    {
        $tasks = $project->tasks()
            ->whereHas('assigned', function ($query) use ($user) {
                $query->where('id', $user->id);
            });

        if ($filter != 'all') {
            $tasks->whereRaw("UPPER(status) = '" . strtoupper($filter) . "'");
        }

        if (request()->has('sort') && !empty(request()->sort)) {
            list($sortName, $sortValue) = parseSearchParam(request());
            $tasks->orderBy($sortName, $sortValue);
        } else {
            $tasks->orderBy('status', 'desc')->orderBy('id', 'asc');
        }

        return $tasks->paginate(request()->per_page ?? $this->paginate);
    }
}