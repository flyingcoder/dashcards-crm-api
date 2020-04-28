<?php

namespace App\Traits;

use Carbon\Carbon;

trait HasTimers
{
	public function totalTimeThisWeek()
    {
        $timers = $this->timers()
                    ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->get();
        return $this->calculateTime($timers);
    }

    public function lastTimer()
    {
        return $this->timers()->latest()->first();
    }
    

    public function userTaskTimers()
    {
    	return $this->tasks()->with('timers');
    }

    public function paginatedUserTimers()
    {
    	$tasks = $this->userTaskTimers();

        if(request()->has('sort') && !empty(request()->sort)) {
            list($sortName, $sortValue) = parseSearchParam(request());
            $tasks->orderBy($sortName, $sortValue);
        } else {
        	$tasks->orderBy('tasks.id', 'desc');
        }

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        if(request()->has('all') && request()->all)
            $data = $tasks->get();
        else
            $data = $tasks->paginate($this->paginate);
        
        $data->map(function ($task) {
            $timer['total_time'] = $task->timers->isEmpty() ? '00:00:00' : $this->calculateTime($task->timers);
        });

        return $data;
    }

    public function paginatedAllTimers()
    {
    	$timers = $this->timers();

        if(request()->has('sort') && !empty(request()->sort)) {

            list($sortName, $sortValue) = parseSearchParam(request());

            $timers->orderBy($sortName, $sortValue);
        }

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        if(request()->has('all') && request()->all)
            $data = $timers->get();
        else
            $data = $timers->paginate($this->paginate);
        
        return $data;
    }

    public function calculateTime($timers)
    {
    	$total_seconds = 0;
    	foreach ($timers as $key => $timer) {
    		if(is_null($timer->properties))
                continue;
            $prop = $timer->properties;
            $total_seconds += (int) $prop['total_seconds']; 
    	}
    	return secondsForHumans($total_seconds);
    }
}