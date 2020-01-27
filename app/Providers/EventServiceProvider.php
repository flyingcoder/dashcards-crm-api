<?php

namespace App\Providers;

use App\Company;
use App\Observers\CompanyObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\NewActivity' => [
                'App\Listeners\NewActivityListener ',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->customObservers();
    }

    protected function customObservers()
    {
        Company::observe(CompanyObserver::class);
    }
}
