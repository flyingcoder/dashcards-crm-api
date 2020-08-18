<?php

namespace App\Http\Controllers;

use App\Comment;
use App\EventModel;
use App\Notifications\CompanyNotification;
use App\Repositories\CalendarEventRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

/**
 * Class EventController
 * @package App\Http\Controllers
 */
class EventController extends Controller
{
    /**
     * @var CalendarEventRepository
     */
    protected $repo;
    protected $alarm_before = 15; //in minutes
    /**
     * EventController constructor.
     * @param CalendarEventRepository $repo
     */
    public function __construct(CalendarEventRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $user = auth()->user();

        return $this->repo->getPaginatedEvents($user);
    }

    /**
     * @return array
     */
    public function attributes()
    {
        $user = auth()->user();

        return $this->repo->getAttributes($user);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        request()->validate([
            'participants' => 'array',
            'notify' => 'boolean',
            'all_day' => 'boolean',
            'start_date' => 'required',
            'end_date' => 'sometimes',
            'title' => 'required|string|min:2',
            'descriptions' => 'string',
            'event_type' => 'required|exists:event_types,id'
        ]);

        try {
            DB::beginTransaction();
            $timezone = request()->has('timezone') ? request()->timezone : 'UTC';
            $start_date = Carbon::createFromFormat('Y-m-d H:i', request()->start_date, $timezone);
            $end_date = $start_date->copy()->endOfDay();
            $utc_start_datetime = $start_date->copy()->setTimezone('UTC');
            $utc_end_datetime = $end_date->copy()->setTimezone('UTC');
            $user = auth()->user();

            $event = EventModel::create([
                'title' => request()->title,
                'all_day' => request()->all_day,
                'start' => $start_date->toDateTimeString(),
                'end' => $end_date->toDateTimeString(),
                'remind_at' => $utc_start_datetime->copy()->subMinutes($this->alarm_before)->toDateTimeString(),
                'utc_start' => $utc_start_datetime->toDateTimeString(),
                'utc_end' => $utc_end_datetime->toDateTimeString(),
                'timezone' => $timezone,
                'description' => request()->description,
                'eventtypes_id' => request()->event_type,
                'properties' => [
                    'send_notify' => request()->notify ?? 0,
                    'alarm' => request()->alarm ?? false,
                    'creator' => $user->id,
                    'link' => request()->link ?? ''
                ],
            ]);

            $event->users()->attach($user->id, ['added_by' => $user->id]);
            $invited = [];
            if (request()->has('participants') && !empty(request()->participants)) {
                foreach (request()->participants as $key => $participant) {
                    if ($participant != $user->id) {
                        $invited[] = $participant;
                        $event->users()->attach($participant, ['added_by' => $user->id]);
                    }
                }
            }

            DB::commit();

            $event->load('users');
            $event->event_type = $event->eventType;

            if (!empty($invited)) {
                company_notification(array(
                    'targets' => $invited,
                    'title' => 'Calendar event',
                    'message' => 'You are invited by ' . $user->first_name . ' to join an event.',
                    'type' => 'event_invitation',
                    'path' => "/dashboard/calendar"
                ));
            }

            return $event;
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id)
    {
        request()->validate([
            'participants' => 'array',
            'notify' => 'boolean',
            'all_day' => 'boolean',
            'start_date' => 'required',
            'end_date' => 'sometimes',
            'title' => 'required|string|min:2',
            'descriptions' => 'string',
            'event_type' => 'required|exists:event_types,id'
        ]);

        try {
            DB::beginTransaction();
            $timezone = request()->has('timezone') ? request()->timezone : 'UTC';
            $start_date = Carbon::createFromFormat('Y-m-d H:i', request()->start_date, $timezone);
            $end_date = $start_date->copy()->endOfDay();
            $utc_start_datetime = $start_date->copy()->setTimezone('UTC');
            $utc_end_datetime = $end_date->copy()->setTimezone('UTC');

            $user = auth()->user();
            $event = EventModel::findOrFail($id);
            $event->title = request()->title;
            $event->all_day = request()->all_day;
            $event->remind_at = $utc_start_datetime->copy()->subMinutes($this->alarm_before)->toDateTimeString();
            $event->start = $start_date->toDateTimeString();
            $event->utc_start = $utc_start_datetime->toDateTimeString();
            $event->end = $end_date->toDateTimeString();
            $event->utc_end = $utc_end_datetime->toDateTimeString();
            $event->description = request()->description;
            $event->eventtypes_id = request()->event_type;

            $props = $event->properties;
            $props['send_notify'] = request()->alarm ?? 0;
            $props['alarm'] = request()->alarm ?? false;
            $props['link'] = request()->link ?? null;
            $event->properties = $props;
            $event->save();

            $event->users()->detach();
            $invited = [];
            if (request()->has('participants') && !empty(request()->participants)) {
                foreach (request()->participants as $key => $participant) {
                    $event->users()->attach($participant, ['added_by' => $user->id]);
                    if ($participant != $user->id) {
                        $invited[] = $participant;
                    }
                }
            }
            DB::commit();

            $event = $event->fresh();
            $event->load('users');
            $event->event_type = $event->eventType;

            if (!empty($invited)) {
                company_notification(array(
                    'targets' => $invited,
                    'title' => 'Calendar event',
                    'message' => 'The event you are invited at has been updated',
                    'type' => 'event_invitation',
                    'path' => "/dashboard/calendar"
                ));
            }

            return $event;
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        //add policy on deleting
        $event = EventModel::findOrFail($id);

        if ($event->delete()) {
            return response()->json(['message' => 'Succesfully delete event.'], 200);
        }

        return response()->json(['message' => 'Can`t delete this event.'], 500);
    }

    /**
     * @param $id
     * @param null $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function leaveEvent($id, $user_id = null)
    {
        $user_id = !is_null($user_id) ? $user_id : auth()->user()->id;

        $event = EventModel::findOrFail($id);

        $event->users()->detach($user_id);

        return response()->json(['message' => 'Successfully remove from event', 'user_id' => $user_id], 200);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function addParticipants($id)
    {
        request()->validate([
            'participants' => 'required|array'
        ]);

        $event = EventModel::findOrFail($id);

        $event->users()->sync(request()->participants);

        return $event->users;
    }

    /**
     * @param $event_id
     * @return mixed
     */
    public function getComments($event_id)
    {
        $event = EventModel::findOrFail($event_id);

        return $event->comments->load(['causer']);
    }

    /**
     * @param $event_id
     * @return mixed
     */
    public function addComment($event_id)
    {
        $event = EventModel::findOrFail($event_id);

        request()->validate([
            'comment' => 'required'
        ]);

        $comment = new Comment([
            'body' => request()->comment,
            'causer_id' => auth()->user()->id,
            'causer_type' => 'App\User'
        ]);

        $new_comment = $event->comments()->save($comment);
        $new_comment->load('causer');

        return $new_comment;
    }

    /**
     * @param $id
     * @param $comment_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeComment($id, $comment_id)
    {
        $event = EventModel::findOrFail($id);

        $comment = $event->comments()->where('id', $comment_id)->first();

        if ($comment->delete()) {
            return response()->json(['comment_id' => $id, 'message' => 'Successfully deleted'], 200);
        }

        return response()->json(['message' => "Can't delete comment."], 500);
    }
}
