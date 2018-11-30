<?php

namespace App\Http\Controllers;

use App\Task;
use App\Timer;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TimerController extends Controller
{
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
}
