<?php

namespace App\Http\Controllers;

use App\Task;
use App\Timer;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TimerController extends Controller
{
    public function timer($action)
    {
        $timer = new Timer();

        return $timer->trigger($action);
    }
}
