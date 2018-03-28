<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Timer;

class CutOffTimer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timer:cutoff';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will cut the timer off if exceed time limit';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        //group by causer_id and subject_id
        $timers = Timer::where('status', 'open')
                      ->get();

        //16 hours max
        $limit = 16*60*60;

        foreach ($timers as $key => $timer) {
            if($timer->action == 'start'){
                Carbon::parse($timer->created_at);
            }
        }
    }
}
