<?php

namespace App\Repositories;

use App\Company;

class ProjectRepository
{
	protected $paginate =  12;

	public function __construct()
	{
		$this->paginate = request()->has('per_page') ? request()->per_page : 12;
	}

	public function getCompanyProjectsList(Company $company)
	{
		$projects = $company->companyProjects()
					->ofType('project')
					->with([ 'manager', 'client', 'members' ])
					->get();
		return $projects;
	}

	public function getCompanyProjects(Company $company, $request)
	{
		$viewer = auth()->user();
		list($sortName, $sortValue) = parseSearchParam($request);

		if($request->has('per_page') && is_numeric($request->per_page)) {
            $this->paginate = $request->per_page;
        }

		$projects = $company->companyProjects()
					->ofType('project');
		
		if (!$viewer->hasRoleLike('admin')) {
			$projects->whereHas('team', function($query) use($viewer) {
				$query->where('user_id', $viewer->id);
			});
		}

		if($request->has('status')){
            $projects->where('status', $request->status);
		}

        if($request->has('sort') && !empty($request->sort)){
            $projects->orderBy($sortName, $sortValue);
        } else {
            $projects->latest();
        }

		if ($request->has('search') && !empty($request->search)) {
			$search = trim($request->search);
			$projects->where(function($query) use ($search){
				$query->where('projects.name', 'like', "%$search%")
					->orWhere('projects.description','like', "%$search%");
			});
		}
        $projects->with([ 'manager', 'client', 'members' ]);

		$projects = $projects->paginate($this->paginate);

		$items = $projects->getCollection();
        $data = collect([]);
        foreach ($items as $key => $project) {
            $clientCompany = Company::find($project->client[0]->props['company_id'] ?? null);
            $data->push(array_merge($project->toArray(),[
            		'expand' => false, 
            		'company_name' => $clientCompany ? $clientCompany->name : "",
            		'location' => $clientCompany ? $clientCompany->address : ($project->client[0]->location ?? '')
            	]));   
        }
        $projects->setCollection($data);

		return $projects;
	}
}