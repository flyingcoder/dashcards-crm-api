<?php

namespace App\Console\Commands;

use App\Configuration;
use App\Traits\HasConfigTrait;
use Illuminate\Console\Command;

class EventConfiguration extends Command
{
    use  HasConfigTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:configuration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     */
    public function handle()
    {
        $this->addAllowedEmailEvents();

        echo "Done!";
    }

    /**
     * void
     */
    public function addAllowedEmailEvents()
    {
        $defaults = config('mail.email_events', []);
        print_r($defaults);
        $fromDB = $this->getConfigByKey('email_events', false);
        if ($fromDB) {
            $value = (array) $fromDB + $defaults;
            $this->setConfigByKey('email_events', $value , $type = 'object');
        } else {
            $this->setConfigByKey('email_events', $defaults , $type = 'object');
        }
    }
}
