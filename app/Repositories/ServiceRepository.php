<?php

namespace App\Repositories;

use App\Company;

class ServiceRepository
{
	protected $paginate =  20;

	public function __construct()
	{
		$this->paginate = request()->has('per_page') ? request()->per_page : 20;
	}


	public function getCompanyServicesList(Company $company)
	{
		$services = $company->services()
					->get();
		return $services;
	}

	public function getCompanyServices(Company $company)
	{
		$services = $company->services()
			->with([ 'managers', 'client', 'members' ]);
		
		if (request()->has('search') && !empty(request()->search)) {
			$keyword = request()->search;
			$services = $services->where(function($query) use ($search){
				$
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