<?php

namespace App\Http\Controllers;

use App\CalendarModel;
use App\EventType;
use App\Repositories\CalendarEventRepository;
use Illuminate\Http\Request;

class CalendarController extends Controller
{

    protected $repo;

    public function __construct(CalendarEventRepository $repo)
    {
        $this->repo = $repo;
    }
    
    public function index()
    {
    	$company = auth()->user()->company();

        if(!request()->ajax())
            return view('pages.calendar');

        return $company->allPaginatedCalendar(request());
    }


    public function calendar()
    {
    	//policy will be added soon
        $user = auth()->user();
        $calendar = CalendarModel::where('user_id', $user->id)->first();

        if (!$calendar) {
            $calendar = CalendarModel::create([
                'company_id' => $user->company()->id,
                'title' => 'My Calendar',
                'description' => ucwords($user->fullname.' calendar'),
                'user_id' => $user->id,
                'created_at' => now()->format('Y-m-d H:i:s')
            ]);
        }

        $calendar->event_types = $this->repo->getEventTypes($user);
    	
        return response()->json([
                'calendar' => $calendar,
                'attributes' => $this->repo->getAttributes($user)
            ], 200); 
    }

    public function attributes()
    {
        $user = auth()->user();
        return response()->json( $this->repo->getAttributes($user) , 200); 
    }

    public function addEventType()
    {
        request()->validate([
            'name' => 'required|string|min:2',
            'color' => 'required'
        ]);

        $eventType = EventType::create([
            'properties' => ['color' => request()->color ],
            'created_by' => auth()->user()->id,
            'company_id'=> auth()->user()->company()->id,
            'name' => request()->name
        ]);

        return $eventType;
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
