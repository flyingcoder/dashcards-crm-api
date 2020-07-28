<?php

namespace App\Console;

use App\Console\Commands\GenerateRecurringInvoices;
use App\Console\Commands\RunScheduleEmails;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Tolawho\Loggy\Facades\Loggy;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        GenerateRecurringInvoices::class,
        RunScheduleEmails::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('generate-recurring-invoice')->dailyAt('10:00'); //everyMinute(); //

        $schedule->command('telescope:prune --hours=24')->hourly();

        $schedule->command('schedule-tasks:email')->everyFiveMinutes()->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
