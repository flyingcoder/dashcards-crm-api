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
            ->with(['managers', 'client', 'members', 'service']);

        if (auth()->check() && auth()->user()->hasRoleLike('client') && !auth()->user()->hasRoleLike('admin')) {
            $services->whereHas('client', function ($query) {
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

        $services = $services->paginate($this->paginate);
        $services->map(function ($service) {
            $service->expand = false;
        });

        return $services;
    }
}