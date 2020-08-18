<?php

namespace App\Console\Commands;

use App\EventModel;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalendarNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calendar:events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process calendar events for notifications';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $current_tick = now();
        $now = $current_tick->copy()->format('Y-m-d H:i:00');
        $events_affected = EventModel::where('properties->alarm', true)
            ->with('users')
            ->whereNotNull('utc_start')
            ->whereNotNull('remind_at')
            ->where('remind_at', '<=', $now)
            ->whereRaw('remind_at <= utc_start')
            ->get();
        if (!$events_affected->isEmpty()) {
            foreach ($events_affected as $event) {

                $utc_start = Carbon::createFromFormat('Y-m-d H:i:00', $event->utc_start);
                $remind_at = Carbon::createFromFormat('Y-m-d H:i:00', $now);
                $participants = $event->users->pluck('id')->toArray();
                $time_left = $remind_at < $utc_start ? $utc_start->diffInMinutes($remind_at) : 0;
                $message = $time_left <= 0 ? "Event is now starting" : $time_left . ' minute(s) before event start';

                // add remind_at + 5 min for next tick of notification
                $next_remind_at = $current_tick->copy()->addMinutes(5)->format('Y-m-d H:i:00');
                if ($next_remind_at > $utc_start)
                    $next_remind_at = $utc_start;

                $event->remind_at = $time_left <= 0 ? null : $next_remind_at;
                $event->save();

                $user = $event->users->first();
                company_notification([
                    'targets' => $participants,
                    'title' => $event->title,
                    'message' => $message,
                    'type' => 'event_countdown',
                    'path' => "/dashboard/calendar",
                    'notif_only' => true
                ], $user);
            }
        }
        echo('Done! Affected : ' . $events_affected->count());
    }
}
