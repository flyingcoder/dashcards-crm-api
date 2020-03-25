<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CalendarModel;

class CalendarController extends Controller
{
    public function index()
    {
    	$company = auth()->user()->company();

        if(!request()->ajax())
            return view('pages.calendar');

        return $company->allPaginatedCalendar(request());
    }

    public function FunctionName($value='')
    {
        # code...
    }

    public function calendar($id)
    {
    	$calendar = CalendarModel::findOrFail($id);

    	//policy will be added soon

    	return $calendar;
    }

    public function events($id)
    {
    	$calendar = CalendarModel::findOrFail($id);

    	return $calendar->events;
    }

    public function addEvent()
    {
        request()->validate([
            'title' => 'required'
        ]);

        $calendar = CalendarModel::findOrFail($id);
    }

    public function store()
    {
    	request()->validate([
            'title' => 'required'
        ]);

        $company = auth()->user()->company();

        $data = [
            'title' => request()->title,
            'company_id' => $company->id
        ];

        if(request()->has('description'))
            $data['description'] = request()->description;

        if(request()->has('properties'))
            $data['properties'] = request()->properties;

        return CalendarModel::create($data);
    }
}
