<?php

namespace App\Providers;

use App\Company;
use App\MediaLink;
use App\Observers\ActivityObserver;
use App\Observers\CompanyObserver;
use App\Observers\MediaLinkObserver;
use App\Observers\ProjectObserver;
use App\Observers\ServiceObserver;
use App\Project;
use App\Service;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\Models\Activity;

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

        if (Schema::hasTable('companies')) {
            $this->customObservers();
        }
    }

    protected function customObservers()
    {
        Company::observe(CompanyObserver::class);
        MediaLink::observe(MediaLinkObserver::class);
        Activity::observe(ActivityObserver::class);
        Project::observe(ProjectObserver::class);
        Service::observe(ServiceObserver::class);
    }
}
