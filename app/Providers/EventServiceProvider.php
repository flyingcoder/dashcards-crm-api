<?php

namespace App\Providers;

use App\Campaign;
use App\Company;
use App\Listeners\InvoicePaidListener;
use App\Listeners\InvoiceReminderListener;
use App\Listeners\InvoiceSendListener;
use App\Listeners\NewActivityListener;
use App\Listeners\NewClientCreatedListener;
use App\Listeners\NewProjectCreatedListener;
use App\Listeners\NewTaskCreatedListener;
use App\Listeners\NewTeamMemberListener;
use App\Listeners\NewUserCreatedListener;
use App\Listeners\QuestionnaireResponseListener;
use App\Listeners\TaskUpdatedListener;
use App\MediaLink;
use App\Observers\ActivityObserver;
use App\Observers\CampaignObserver;
use App\Observers\CompanyObserver;
use App\Observers\MediaLinkObserver;
use App\Observers\ProjectObserver;
use App\Project;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
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
            NewActivityListener::class,
        ],
        'App\Events\NewProjectCreated' => [
            NewProjectCreatedListener::class,
        ],
        'App\Events\NewTaskCreated' => [
            NewTaskCreatedListener::class,
        ],
        'App\Events\TaskUpdated' => [
            TaskUpdatedListener::class,
        ],
        'App\Events\NewUserCreated' => [
            NewUserCreatedListener::class,
            NewTeamMemberListener::class,
        ],
        'App\Events\NewClientCreated' => [
            NewClientCreatedListener::class,
        ],
        'App\Events\InvoiceSend' => [
            InvoiceSendListener::class,
        ],
        'App\Events\InvoiceReminder' => [
            InvoiceReminderListener::class
        ],
        'App\Events\InvoicePaid' => [
            InvoicePaidListener::class,
        ],
        'App\Events\QuestionnaireResponse' => [
            QuestionnaireResponseListener::class,
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

    /**
     *
     */
    protected function customObservers()
    {
        Company::observe(CompanyObserver::class);
        MediaLink::observe(MediaLinkObserver::class);
        Activity::observe(ActivityObserver::class);
        Project::observe(ProjectObserver::class);
        Campaign::observe(CampaignObserver::class);
    }
}
