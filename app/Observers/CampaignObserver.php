<?php

namespace App\Observers;

use App\Campaign;
use Chat;

class CampaignObserver
{
    /**
     * Handle the service "created" event.
     *
     * @param  \App\Campaign  $campaign
     * @return void
     */
    public function created(Campaign $campaign)
    {
        $participants = collect($campaign->members()->select('id')->get());

        $participants->flatten();

        $client_convo = Chat::createConversation($participants->all());
        $client_convo->project_id = $campaign->id;
        $client_convo->type = 'client';
        $client_convo->save();

        $team_convo = Chat::createConversation($participants->all());
        $team_convo->project_id = $campaign->id;
        $team_convo->type = 'team';
        $team_convo->save();
    }

    /**
     * Handle the service "updated" event.
     *
     * @param  \App\Campaign  $campaign
     * @return void
     */
    public function updated(Campaign $campaign)
    {
        //
    }

    /**
     * Handle the service "deleting" event.
     *
     * @param  \App\Campaign  $campaign
     * @return void
     */
    public function deleting(Campaign $campaign)
    {
        foreach(['milestones'] as $relation){
            foreach($campaign->{$relation} as $item)  {
                $item->delete();
            }
        }
    }
    
    /**
     * Handle the service "deleted" event.
     *
     * @param  \App\Campaign  $campaign
     * @return void
     */
    public function deleted(Campaign $campaign)
    {
        //
    }

    /**
     * Handle the service "restored" event.
     *
     * @param  \App\Campaign  $campaign
     * @return void
     */
    public function restored(Campaign $campaign)
    {
        $milestones = $campaign->milestones()->onlyTrashed()->get();
        foreach($milestones as $item)  {
            $item->restore();
        }
        
    }

    /**
     * Handle the service "force deleted" event.
     *
     * @param  \App\Campaign  $campaign
     * @return void
     */
    public function forceDeleted(Campaign $campaign)
    {
        //
    }
}
