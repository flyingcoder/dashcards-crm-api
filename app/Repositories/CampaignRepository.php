<?php

namespace App\Repositories;

use App\Company;

class CampaignRepository
{
    protected $paginate = 20;

    /**
     * CampaignRepository constructor.
     */
    public function __construct()
    {
        $this->paginate = request()->has('per_page') ? request()->per_page : 20;
    }


    /**
     * @param Company $company
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCompanyCampaignList(Company $company)
    {
        return $company->campaigns()
            ->get();
    }

    /**
     * @param Company $company
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getCompanyCampaigns(Company $company)
    {
        $services = $company->campaigns()
            ->withCount('tasks')
            ->with(['managers', 'client', 'members', 'service']);

        if (request()->has('service') && request()->service != 'all' && is_numeric(request()->service)) {
            $services->whereHas('service', function ($query) {
                $query->where('id', request()->service);
            });
        }

        if (auth()->check() && auth()->user()->hasRoleLike('client') && !auth()->user()->hasRoleLike('admin')) {
            $services->whereHas('client', function ($query) {
                $query->where('id', auth()->user()->id);
            });
        } elseif (!auth()->user()->hasRoleLikeIn(['admin', 'manager'])){
            $services->whereHas('team', function ($query) {
                $query->where('id', auth()->user()->id);
            });
        }

        if (request()->has('search') && !empty(request()->search)) {
            $search = request()->search;
            $services = $services->where(function ($query) use ($search) {
                $query->where('services.name', 'like', "%$search%")
                    ->orWhere('services.description', 'like', "%$search%");
            });
        }

        return $services->paginate($this->paginate);
    }
}