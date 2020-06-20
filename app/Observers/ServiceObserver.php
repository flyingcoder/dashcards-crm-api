<?php

namespace App\Observers;

use App\Service;
use Chat;

class ServiceObserver
{
    /**
     * Handle the service "created" event.
     *
     * @param  \App\Service  $service
     * @return void
     */
    public function created(Service $service)
    {
        $participants = collect($service->members()->select('id')->get());

        $participants->flatten();

        $client_convo = Chat::createConversation($participants->all());
        $client_convo->project_id = $service->id;
        $client_convo->type = 'client';
        $client_convo->save();

        $team_convo = Chat::createConversation($participants->all());
        $team_convo->project_id = $service->id;
        $team_convo->type = 'team';
        $team_convo->save();
    }

    /**
     * Handle the service "updated" event.
     *
     * @param  \App\Service  $service
     * @return void
     */
    public function updated(Service $service)
    {
        //
    }

    /**
     * Handle the service "deleting" event.
     *
     * @param  \App\Service  $service
     * @return void
     */
    public function deleting(Service $service)
    {
        foreach(['milestones'] as $relation){
            foreach($service->{$relation} as $item)  {
                $item->delete();
            }
        }
    }
    
    /**
     * Handle the service "deleted" event.
     *
     * @param  \App\Service  $service
     * @return void
     */
    public function deleted(Service $service)
    {
        //
    }

    /**
     * Handle the service "restored" event.
     *
     * @param  \App\Service  $service
     * @return void
     */
    public function restored(Service $service)
    {
        //
    }

    /**
     * Handle the service "force deleted" event.
     *
     * @param  \App\Service  $service
     * @return void
     */
    public function forceDeleted(Service $service)
    {
        //
    }
}
