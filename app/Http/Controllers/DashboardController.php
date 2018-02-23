<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;

class DashboardController extends Controller
{
    public function myProjects(){
        return 1;
    }

    public function myTasks(){
        return 10;
    }

    public function tasks(){
        return Task::all();
    }
}
