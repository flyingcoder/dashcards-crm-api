<?php

namespace App\Repositories;

use App\Task;
use App\Timer;
use App\User;
use Carbon\Carbon;

class TimerRepository
{

	public function getTimerForUser(User $user, $type = 'today')
	{
		$timers = $user->timers();

		if ($type === 'today') {
			$timers = $timers->whereDate('created_at', now()->format('Y-m-d'));
		} elseif ($type === 'monthly') {
			$timers = $timers->whereYear('created_at', now()->year)
                        	->whereMonth('created_at', now()->month);
		}

		$timers = $timers->get();

		if ($timers->isEmpty()) {
			return parseSeconds(0);
		}

		$last_timer = $timers->last();
		if (is_null($last_timer->properties) ) {
			$assumed = new Timer;

			$start = Carbon::parse($last_timer->created_at);
	        $end = Carbon::now();
	        $total_sec = $end->diffInSeconds($start);

			$assumed->properties = ['total_seconds' => $total_sec,'total_time' => gmdate("H:i:s", $total_sec)];

			$timers->push($assumed);
		}

		return array_merge((array) $this->calculateTime($timers), ['interval' => null]);
	}

	public function getTimerForTask(Task $task)
	{
		$timers = $task->timers;

		$data = [
			'timer_stats' => $this->calculateTime($timers),
			'timer_status' => $task->timerStatus(),
			'timer_interval' => null,
			'timer_started' => false,
			'timer_disabled' => $task->status === 'completed'
		];

		return $data;
	}

	protected function calculateTime($timers)
    {
    	$total_seconds = 0;
    	foreach ($timers as $key => $timer) {
    		if(is_null($timer->properties))
                continue;
            $prop = $timer->properties;
            $total_seconds += (int) $prop['total_seconds']; 
    	}

    	return parseSeconds($total_seconds);
    }
}