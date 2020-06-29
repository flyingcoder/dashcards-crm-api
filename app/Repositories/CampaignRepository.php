<?php

namespace App\Repositories;

use App\Company;

class CampaignRepository
{
	protected $paginate =  20;

	public function __construct()
	{
		$this->paginate = request()->has('per_page') ? request()->per_page : 20;
	}


	public function getCompanyCampaignList(Company $company)
	{
		$services = $company->campaigns()
					->get();
		return $services;
	}

	public function getCompanyCampaigns(Company $company)
	{
		$services = $company->campaigns()
			->with([ 'managers', 'client', 'members', 'service' ]);
		
		if (request()->has('search') && !empty(request()->search)) {
			$search = request()->search;
			$services = $services->where(function($query) use ($search){
				$query->where('services.name', 'like', "%$search%")
					->orWhere('services.description','like', "%$search%");
			});
		}

		$services = $services->paginate($this->paginate);
		$services->map(function($service){
			$service->expand = false;
		});

		return $services;
	}
}