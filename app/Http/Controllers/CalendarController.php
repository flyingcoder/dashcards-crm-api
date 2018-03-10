<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Calendar;

class CalendarController extends Controller
{
    public function index()
    {
    	$company = auth()->user()->company();

        if(!request()->ajax())
            return view('pages.calendar');

        return $company->allPaginatedCalendar(request());
    }

    public function calendar($id)
    {
    	$calendar = Calendar::findOrFail($id);

    	//policy will be added soon

    	return $calendar;
    }

    public function events($id)
    {
    	$calendar = Calendar::findOrFail($id);

    	//policy will be added

    	return $calendar->events;
    }

    public function store()
    {
    	request()->validate([
    		'title' => 'required'
    	]);

    	$company = auth()->user()->company();

    	$company->calendars()->create(request());

    	return Calendar::latest()->first();
    }
}
