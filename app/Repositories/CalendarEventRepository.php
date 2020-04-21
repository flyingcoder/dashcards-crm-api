<?php

namespace App\Repositories;

use App\CalendarModel;
use App\EventModel;
use App\EventType;
use App\User;
use Illuminate\Support\Facades\DB;

class CalendarEventRepository
{
	protected $company_id;

	protected $pagination = 10;

	public function __construct()
	{
		if (auth()->check()) {
			$this->company_id = auth()->user()->company()->id;
		}
	}

	public function setCompany($id)
	{
		$this->company_id = $id;
	}

	public function getEventTypes(User $user)
	{
		$event_types = collect([]);
		// $default_event_types = EventType::whereNull('company_id')->where('is_public', 1);
		$company_event_types = EventType::where('company_id', $user->company()->id)->where('created_by', '<>', $user->id)->where('is_public', 1);
		$personal_event_types = EventType::where('created_by', '=', $user->id);

		$event_types = $personal_event_types
						// ->union($default_event_types)
						->union($company_event_types)
						->orderBy('name', 'ASC')
						->get();
		
		return $event_types->toArray();
	}

	public function getPaginatedEvents(User $user )
	{
		$per_page = request()->has('per_page') ? request()->per_page : $this->pagination;

		$participation = $user->event_participations()->pluck('event_id')->toArray();

		$events = EventModel::whereIn('id', $participation);
		if (request()->has('date') && !empty(request()->date)) {
			$date = request()->date;

			$events = $events->whereRaw('? between date(`start`) and date(`end`)', array($date));
		}

		if (request()->has('alarm') && request()->alarm) {
			$events = $events->where('properties->alarm', true);
		}
		
		$events = $events->orderBy('start','ASC')->paginate($per_page);

		$events->map(function($event) {
			$event['participants'] = $event->participants()->with(['user', 'addedBy'])->get()->toArray();
			$event['event_type'] = $event->eventType;
		});

		return $events;
	}

	public function getAttributes(User $user, $from = null, $to = null )
	{

		$from = is_null($from) ? date('Y-01-01') : $from;
		$to = is_null($to) ? date('Y-12-31') : $to;

		$participation = $user->event_participations()->pluck('event_id')->toArray();

		$events = EventModel::whereIn('id', $participation)
					->with('eventType')
					->where('start', '>=', $from)
					->where('end', '<=', $to)
					->orderBy('start','ASC')
					->get();

		$data = array();

		foreach ($events as $key => $event) {
			$data[] = [
				'dot' => $event->eventType->properties['color'],
				'popover' => [
						'label' => $event->title
					],
				'dates' => [
						[ 'start' => $event->start , 'end' => $event->end ]
					]
			];
		}

		return $data;
	}

	public function upcomingEventsForUser(User $user, $count_only = false)
	{
		$query = EventModel::join('event_participants', 'events.id', '=', 'event_participants.event_id')
					->where('event_participants.user_id', '=', $user->id)
					->whereDate('start', '>=', DB::raw('NOW()'))
					->orderBy('events.start','ASC');
		if ($count_only) {
			return $query->count();
		}
		return $query->get();
	}
}
