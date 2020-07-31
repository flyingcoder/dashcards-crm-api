<?php

namespace App\Policies;

use App\Campaign;
use Illuminate\Auth\Access\HandlesAuthorization;

class CampaignPolicy
{
    use HandlesAuthorization;


    /**
     * @param Campaign $campaign
     * @return bool
     */
    protected function sameCompany(Campaign $campaign)
    {
        return ((int) $campaign->company_id == (int) auth()->user()->company()->id);
    }

    /**
     * Determine whether the rob can view the ben.
     *
     * @return mixed
     */
    public function index()
    {
        if( !auth()->user()->hasRoleLikeIn(['admin','manager','default-admin-'.auth()->user()->company()->id]) && auth()->user()->can('view.all-campaign') ) {
            abort(403, 'Not enought permission!');
        }
    }

    /**
     * Determine whether the rob can view the ben.
     *
     * @param Campaign $campaign
     * @return mixed
     */
    public function view(Campaign $campaign)
    {
        if(!$this->sameCompany($campaign)){
            abort(403, 'Campaign not found!');
        }
    }

    /**
     * Determine whether the rob can view the ben.
     *
     * @param Campaign $campaign
     * @return mixed
     */
    public function viewTask(Campaign $campaign)
    {
        if(!$this->sameCompany($campaign)){
            abort(403, 'Campaign not found!');
        }

        if( !auth()->user()->hasRoleLikeIn(['admin','manager']) && auth()->user()->can('view.campaign-task') ) {
            abort(403, 'Campaign Tasks not found!');
        }
    }


    /**
     * Determine whether the ben can create users.
     *
     * @return mixed
     */
    public function create()
    {
        if( !auth()->user()->hasRoleLikeIn(['admin','manager','default-admin-'.auth()->user()->company()->id]) && !auth()->user()->can('create.campaign') ){
            abort(403, 'Not enought permission to create a campaign!');
        }
    }

    /**
     * Determine whether the rob can update the ben.
     *
     * @param $campaign
     * @return mixed
     */
    public function update($campaign)
    {
        if(!$this->sameCompany($campaign)){
            abort(403, 'Campaign not found!');
        }

        if(!auth()->user()->hasRoleLikeIn(['admin','default-admin-'.auth()->user()->company()->id]) && !auth()->user()->can('update.campaign') ){
            abort(403, 'Not enough permission!');
        }
    }

    /**
     * Determine whether the rob can delete the ben.
     *
     * @param Campaign $campaign
     * @return mixed
     */
    public function delete(Campaign $campaign)
    {
        if( !auth()->user()->hasRoleLikeIn(['admin','default-admin-'.auth()->user()->company()->id]) && !auth()->user()->can('delete.campaign') ){
            abort(403, 'Not enough permission!');
        }

        if(!$this->sameCompany($campaign)){
            abort(403, 'Campaign not found!');
        }
    }
}
