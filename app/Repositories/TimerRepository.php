<?php

namespace App\Repositories;

use App\Task;
use App\Timer;
use App\User;
use Carbon\Carbon;

class TimerRepository
{
	public function getTimerForUserFromTo($user, $from_date, $to_date)
	{
		$timers = collect([]);
		$latest_timer = $user->lastTimer();
		$first_start = $user->timers()->where('action', '=', 'start')
						->whereBetween('created_at', [$from_date.' 00:00:00', $to_date.' 23:59:59'])
						->orderBy('created_at', 'ASC')
						->first();
		if (!$first_start) {
			return array_merge(
				(array) parseSeconds(0), 
				[
					'interval' => null,
					'timer_status' =>  '',
					'timer_created' => null,
					'timer_stopped' =>  null,
					'latest_timer' => $latest_timer ?? null 
				]);
		}

		$last_start = $user->timers()->where('action', '=', 'start')
						->whereBetween('created_at', [$from_date.' 00:00:00', $to_date.' 23:59:59'])
						->latest()
						->first();

		$last_stop = $user->timers()->where('action', '=', 'stop')->where('id', '>' ,$last_start->id)->orderBy('created_at', 'ASC')->first();

		if(!$last_stop){
			$timers = $user->timers()->where('id', '>=', $first_start->id)->where('id', '<=', $last_start->id)->get();
			$assumed = $this->fillInOngoing($last_start);
			$timers->push($assumed);
			$timer_stopped = null;
			$timer_status  = 'open';
		} else {
			$timers = $user->timers()->where('id', '>=', $first_start->id)->where('id', '<=', $last_stop->id)->get();
			$timer_stopped = $last_stop->created_at->format('Y-m-d H:i:s');
			$timer_status  = $last_stop->status ?? 'close';
		}
		
		$timer_created = $first_start->created_at->format('Y-m-d H:i:s');
		return array_merge(
				(array) $this->calculateTime($timers), 
				[
					'interval' => null,
					'timer_status' =>  $timer_status,
					'timer_created' => $timer_created,
					'timer_stopped' => $timer_stopped,
					'latest_timer' => $latest_timer ?? null   
				]);
	}
	/**
	 *
	 *  $date in format of Y-m-d
	 */
	public function getTimerForUserByDate(User $user, $date)
	{
		$latest_timer = $user->lastTimer();
		$timers = collect([]);
		$first_start = $user->timers()->where('action', '=', 'start')
						->whereDate('created_at','=', $date)
						->orderBy('created_at', 'ASC')
						->first();

		if (!$first_start) {
			return array_merge(
				(array) parseSeconds(0), 
				[
					'interval' => null,
					'timer_status' =>  '',
					'timer_created' => null,
					'timer_stopped' =>  null,
					'latest_timer' => $latest_timer ?? null 
				]);
		}
		$last_start = $user->timers()->where('action', '=', 'start')
						->whereDate('created_at','>=', $date)
						->orderBy('created_at', 'DESC')
						->first();

		$last_stop = $user->timers()->where('action', '=', 'stop')->where('id', '>' ,$last_start->id)->orderBy('created_at', 'ASC')->first();

		if(!$last_stop){
			$timers = $user->timers()->where('id', '>=', $first_start->id)->where('id', '<=', $last_start->id)->get();
			$assumed = $this->fillInOngoing($last_start);
			$timers->push($assumed);
			$timer_stopped = null;
			$timer_status  = 'open';
		} else {
			$timers = $user->timers()->where('id', '>=', $first_start->id)->where('id', '<=', $last_stop->id)->get();
			$timer_stopped = $last_stop->created_at->format('Y-m-d H:i:s');
			$timer_status  = $last_stop->status ?? 'close';
		}
		
		$timer_created = $first_start->created_at->format('Y-m-d H:i:s');
		return array_merge(
				(array) $this->calculateTime($timers), 
				[
					'interval' => null,
					'timer_status' =>  $timer_status,
					'timer_created' => $timer_created,
					'timer_stopped' => $timer_stopped,
					'latest_timer' => $latest_timer ?? null   
				]);
	}
	/**
	 *
	 * Type: today, monthly, date in format of Y-m-d
	 */
	public function getTimerForUser(User $user, $type = 'today')
	{
		$timers = $user->timers();
		$latest_timer = $user->lastTimer();

		if ($type === 'today') {
			$filteredTimer = (clone $timers)->whereDate('created_at', now()->format('Y-m-d'));
		} elseif ($type === 'monthly') {
			$filteredTimer = (clone $timers)->whereYear('created_at', now()->year)
                        	->whereMonth('created_at', now()->month);
		} else {
			$filteredTimer = (clone $timers)->whereDate('created_at', $type); //since
		}

		$first = (clone $filteredTimer)->where('action', '=', 'start')->orderBy('created_at', 'ASC')->first();

		if (!$first) {
			//no first start record
			return array_merge(
				(array) parseSeconds(0), 
				[
					'interval' => null,
					'timer_status' =>  '',
					'timer_created' => null,
					'timer_stopped' =>  null,
					'latest_timer' => $latest_timer ?? null 
				]);
		}

		$last  = (clone $filteredTimer)->where('id', '>', $first->id)->latest()->first();
		if ($last && $last->action === 'start') {
			//get the next stop that fall beyond the given period
			$last = (clone $timers)->where('action', '=', 'stop')->where('id', '>', $last->id)->orderBy('id', 'ASC')->first();
		} //else assumed last is stopped

		if (!$last) {
			$timers = (clone $timers)->where('id', '>=', $first->id)->get();
			$last   = $timers->last();
			$assumed = $this->fillInOngoing($last);
			$timers->push($assumed);
			$timer_created = $first->created_at->format('Y-m-d H:i:s');
			$timer_stopped = null;
			$timer_status  = 'open';
		} else {
			$timers = (clone $timers)->where('id', '>=', $first->id)->where('id', '<=', $last->id)->get();
			$timer_created = $first->created_at->format('Y-m-d H:i:s');
			$timer_stopped = $last->created_at->format('Y-m-d H:i:s');
			$timer_status  = $last->status ?? 'close';
		}

		return array_merge(
				(array) $this->calculateTime($timers), 
				[
					'interval' => null,
					'timer_status' =>  $timer_status,
					'timer_created' => $timer_created,
					'timer_stopped' => $timer_stopped,
					'latest_timer' => $latest_timer ?? null   
				]);

	}

	public function getTimerForTask(Task $task)
	{
		$timers = $task->timers;

		$last_timer = $timers->last();
		$first_timer = $timers->first();

		if ($last_timer && is_null($last_timer->properties) ) {
			$assumed = $this->fillInOngoing($last_timer);
			$timers->push($assumed);
		}

		$data = [
			'timer_created' => $first_timer && $first_timer->created_at ? $first_timer->created_at->format('Y-m-d H:i:s') : null,
			'timer_stopped' => $last_timer && $last_timer->created_at ? $last_timer->created_at->format('Y-m-d H:i:s') : null,
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

    protected function fillInOngoing($last_timer)
    {
    	$assumed = new Timer;
		$start = Carbon::parse($last_timer->created_at);
        $end = Carbon::now();
	    $total_sec = $end->diffInSeconds($start);
		$assumed->properties = ['total_seconds' => $total_sec,'total_time' => gmdate("H:i:s", $total_sec)];
		return $assumed;
    }
}

