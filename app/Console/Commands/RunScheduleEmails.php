<?php

namespace App\Console\Commands;

use App\Mail\DynamicEmail;
use App\ScheduleTask;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use stdClass;

class RunScheduleEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule-tasks:email {dt?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run schedule tasks for emails';

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
     * @return mixed|void
     */
    public function handle()
    {
        $this->handleNewScheduleTasks();
        $now = Carbon::now('UTC');
        if (!empty(trim($this->argument('dt')))) {
            $now = Carbon::createFromFormat('Y-m-d H:i:s', trim($this->argument('dt')),'UTC');
        }
        $this->handleExecutableScheduleTasks($now);

        echo "Done!";
    }

    /**
     * @param Carbon $now
     * @return mixed|void
     */
    public function handleExecutableScheduleTasks(Carbon $now)
    {
        $to = $now->copy()->addMinutes(5);
        $tasks = ScheduleTask::where('next_run_at', '<=', $to->format('Y-m-d H:i:s'))->get();
        if (!$tasks->isEmpty()) {
            foreach ($tasks as $key => $task) {
                $interval_type = $task->interval_type;
                $target_runtime = null;

                if (!empty($task->props)) {
                    $from = new stdClass();
                    $from->email = $task->props['from'];
                    $from->fullname = '';
                    Mail::to($task->props['to'])->send(
                        new DynamicEmail($task->props['contents'], $task->props['subject'], $from)
                    );
                }

                $target_runtime = Carbon::createFromFormat('Y-m-d H:i:s', $task->next_run_at, 'UTC');
                $task->last_run_at = $target_runtime->copy()->format('Y-m-d H:i:s');
                if ($interval_type == 'every_hour') {
                    $target_runtime->addHour();
                } elseif ($interval_type == 'every_day_at') {
                    $target_runtime->addDay();
                } elseif ($interval_type == 'every_week_at') {
                    $target_runtime->addWeek();
                } elseif ($interval_type == 'every_month_at') {
                    $target_runtime->addMonth();
                }
                $task->next_run_at = $target_runtime->format('Y-m-d H:i:s');
                if ($task->save()) {
                    $task->histories()->create([
                        'props' => $task->props,
                        'interval_at' => $task->interval_at,
                        'interval_type' => $task->interval_type,
                        'run_at' => $task->last_run_at
                    ]);
                }
            }
        }
    }

    /**
     * @return void
     */
    public function handleNewScheduleTasks()
    {
        $tasks = ScheduleTask::whereNull('next_run_at')->get();
        $now = Carbon::now('UTC');
        if (!$tasks->isEmpty()) {
            foreach ($tasks as $key => $task) {
                $timezone = $task->timezone;
                $interval_type = $task->interval_type;
                $interval_at = $task->interval_at;
                $target_runtime = null;

                if ($interval_type == 'every_hour') {
                    $target_runtime = Carbon::createFromFormat('Y-m-d H:i:s', now()->format('Y-m-d H:00:01'), $timezone);
                    $target_runtime->setTimezone('UTC');
                    if ($target_runtime < $now) $target_runtime->addHour();
                } elseif ($interval_type == 'every_day_at') {
                    $target_runtime = Carbon::createFromFormat('Y-m-d g:i A', now()->format('Y-m-d') . $interval_at, $timezone);
                    $target_runtime->setTimezone('UTC');
                    if ($target_runtime < $now) $target_runtime->addDay();
                } elseif ($interval_type == 'every_week_at') {
                    $target_runtime = Carbon::createFromFormat('Y-m-d H:i:s l', now()->format('Y-m-d 00:00:01') . ' ' . $interval_at, $timezone);
                    $target_runtime->setTimezone('UTC');
                    if ($target_runtime < $now) $target_runtime->addWeek();
                } elseif ($interval_type == 'every_month_at') {
                    if ($interval_at == 'last') $interval_at = date('t');
                    $target_runtime = Carbon::createFromFormat('Y-m-d H:i:s', now()->format("Y-m-$interval_at 00:00:01"), $timezone);
                    $target_runtime->setTimezone('UTC');
                    if ($target_runtime < $now) $target_runtime->addMonth();
                }
                $task->next_run_at = $target_runtime->toDateTimeString();
                $task->save();
            }
        }
    }
}
