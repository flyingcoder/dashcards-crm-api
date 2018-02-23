<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('pages.project-hq.reports', ['project_id' => request()->project_id]);        
    }
}
