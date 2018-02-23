<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class TimerController extends Controller
{
    public function taskTimerStart()
    {
    	$task = Task::findOrFail(request()->task_id);

    	Task::create([
    		'timer_name' => 'Task Timer',
    		'description' => 'A timer for task',
    		'subject_id' => request()->task_id,
    		'subject_type' => 'App\Task',
    		'causer_id' => auth()->user()->id,
    		'causer_type' => 'App\User',
    		'properties' => '{}',
    	]);
    }
}
