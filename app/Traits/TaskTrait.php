<?php


namespace App\Traits;


trait TaskTrait
{
    /**
     * @param bool $own_task
     * @return array
     */
    public function taskCounters($own_task = false)
    {
        return [
            'open' => $this->taskStatusCounter('open', $own_task),
            'behind' => $this->taskStatusCounter('behind', $own_task),
            'completed' => $this->taskStatusCounter('completed', $own_task),
            'pending' => $this->taskStatusCounter('pending', $own_task),
            'urgent' => $this->taskStatusCounter('urgent', $own_task)
        ];
    }

    /**
     * @param $status
     * @param bool $own_task
     * @return int
     */
    public function taskStatusCounter($status, $own_task = false)
    {
        if ($own_task) {
            return $this->tasks()->whereHas('assigned', function ($query) {
                $query->where('id', auth()->user()->id);
            })->count();
        }
        return $this->tasks()->where('tasks.status', $status)->count();
    }
}