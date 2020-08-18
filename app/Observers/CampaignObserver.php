<?php

namespace App\Observers;

use App\Campaign;
use App\Traits\ConversableTrait;

class CampaignObserver
{
    use ConversableTrait;
    /**
     * Handle the service "created" event.
     *
     * @param \App\Campaign $campaign
     * @return void
     */
    public function created(Campaign $campaign)
    {
        $campaign->clientProjectRoom();
        $campaign->teamProjectRoom();
    }

    /**
     * Handle the service "updated" event.
     *
     * @param \App\Campaign $campaign
     * @return void
     */
    public function updated(Campaign $campaign)
    {
        $campaign->updateTeamProjectRoomUsers();
        $campaign->updateClientProjectRoomUsers();
    }

    /**
     * Handle the service "deleting" event.
     *
     * @param \App\Campaign $campaign
     * @return void
     */
    public function deleting(Campaign $campaign)
    {
        foreach (['milestones'] as $relation) {
            foreach ($campaign->{$relation} as $item) {
                $item->delete();
            }
        }
    }

    /**
     * Handle the service "deleted" event.
     *
     * @param \App\Campaign $campaign
     * @return void
     */
    public function deleted(Campaign $campaign)
    {
        //
    }

    /**
     * Handle the service "restored" event.
     *
     * @param \App\Campaign $campaign
     * @return void
     */
    public function restored(Campaign $campaign)
    {
        $milestones = $campaign->milestones()->onlyTrashed()->get();
        foreach ($milestones as $item) {
            $item->restore();
        }

    }

    /**
     * Handle the service "force deleted" event.
     *
     * @param \App\Campaign $campaign
     * @return void
     */
    public function forceDeleted(Campaign $campaign)
    {
        //
    }
}
