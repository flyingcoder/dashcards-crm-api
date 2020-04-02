<?php

namespace App\Http\Controllers;

use App\CalendarModel;
use App\EventModel;
use App\Repositories\CalendarEventRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    protected $repo;

    public function __construct(CalendarEventRepository $repo)
    {
        $this->repo = $repo;
    }
    
    public function index()
    {
    	$user = auth()->user();

        $events = $this->repo->getPaginatedEvents($user);

    	return $events;
    }

    public function attributes()
    {
    	$user = auth()->user();
    	
        $attributes = $this->repo->getAttributes($user);

        return $attributes;
    }

    public function store()
    {
    	request()->validate([
    		'participants' => 'array',
    		'notify' => 'boolean',
    		'all_day' => 'boolean',
    		'start_date' => 'required',
    		'end_date' => 'required',
    		'title' => 'required|string|min:2',
    		'descriptions' => 'string',
    		'event_type' => 'required|exists:event_types,id'
    	]);

    	try {
    		DB::beginTransaction();

	    	$user = auth()->user();

	    	$event = EventModel::create([
	    		'title' => request()->title,
	    		'all_day' => request()->all_day,
	    		'start' => request()->start_date,
	    		'end' => request()->end_date,
	    		'description' => request()->description,
	    		'eventtypes_id' => request()->event_type,
	    		'properties' => [
	    				'timezone' => request()->has('timezone') ? request()->timezone : 'UTC',
	    				'send_notify' => request()->notify ?? 0,
	    				'creator' => $user->id
		    		],
	    	]);
	    	
	    	$event->participants()->create(['user_id' => $user->id, 'added_by' => $user->id]);
	    	if (request()->has('participants') && !empty(request()->participants)) {
	    		foreach (request()->participants as $key => $participant) {
	    			$event->participants()->create(['user_id' => $participant, 'added_by' => $user->id]);
	    		}
	    	}

	    	DB::commit();
	    	
	    	$event->participants = $event->participants()->with(['user', 'addedBy'])->get()->toArray();
	    	$event->event_type = $event->eventType;

	    	return $event;
    	} catch (\Exception $e) {
    		DB::rollback();
    		return response()->json(['message' => $e->getMessage() ], 500);
    	}
    }

    public function update($id)
    {
        request()->validate([
            'participants' => 'array',
            'notify' => 'boolean',
            'all_day' => 'boolean',
            'start_date' => 'required',
            'end_date' => 'required',
            'title' => 'required|string|min:2',
            'descriptions' => 'string',
            'event_type' => 'required|exists:event_types,id'
        ]);

        try {
            DB::beginTransaction();
            $user = auth()->user();
            $event = EventModel::findOrFail($id);
            $event->title = request()->title;
            $event->all_day = request()->all_day;
            $event->start = request()->start_date;
            $event->end = request()->end_date;
            $event->description = request()->description;
            $event->eventtypes_id = request()->event_type;
            $event->properties = [
                            'timezone' => request()->has('timezone') ? request()->timezone : 'UTC',
                            'send_notify' => request()->notify ?? 0,
                            'creator' => $user->id
                        ];
            $event->save();

            $event->participants()->delete();

            if (request()->has('participants') && !empty(request()->participants)) {
                foreach (request()->participants as $key => $participant) {
                    $event->participants()->create(['user_id' => $participant, 'added_by' => $user->id]);
                }
            }
            DB::commit();

            $event->participants = $event->participants()->with(['user', 'addedBy'])->get()->toArray();
            $event->event_type = $event->eventType;

            return $event;
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage() ], 500);
        }
    }

    public function delete($id)
    {
        //add policy on deleting
        $event = EventModel::findOrFail($id);

        if ($event->delete()) {
            return response()->json(['message' => 'Succesfully delete event.'], 200);
        }

        return response()->json(['message' => 'Can`t delete this event.'], 500);
    }

    public function leaveEvent($id, $user_id = null)
    {
        $user_id = !is_null($user_id) ? $user_id : auth()->user()->id; 

        $event = EventModel::findOrFail($id);

        if ($event->properties['creator'] === $user_id ) {
            return response()->json(['message' => 'Cannot remove self from own event'], 500);
        }

       $event->participants()->where('user_id', $user_id)->delete();

        return response()->json(['message' => 'Successfully remove from event', 'user_id' => $user_id], 200);
    }
}
