<?php

namespace App\Http\Controllers;

use App\Task;
use App\Timer;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TimerController extends Controller
{
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
}
