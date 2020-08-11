<?php


namespace App\Traits;


trait TaskTrait
{
    /**
     * @param bool $own_task
     * @param null $source
     * @return array
     */
    public function taskCounters($own_task = false, $source = null)
    {
        return [
            'all' => $this->taskStatusCounter('all', $own_task, $source),
            'completed' => $this->taskStatusCounter('completed', $own_task, $source),
            'open' => $this->taskStatusCounter('open', $own_task, $source),
            'behind' => $this->taskStatusCounter('behind', $own_task, $source),
            'urgent' => $this->taskStatusCounter('urgent', $own_task, $source)
        ];
    }

    /**
     * @param $status
     * @param bool $own_task
     * @param null $source
     * @return int
     */
    public function taskStatusCounter($status, $own_task = false, $source = null)
    {
        $countQuery = !is_null($source) ? $source->tasks() : $this->tasks();
        if ($own_task) {
            $countQuery->whereHas('assigned', function ($query) {
                $query->where('id', auth()->user()->id);
            });
        }

        if ($status != 'all'){
            $countQuery->whereRaw("UPPER(status) = '". strtoupper($status)."'");
        }

        return $countQuery->count();
    }
}