<?php

namespace App\Repositories;
use App\Company;
use App\Form;
use App\FormResponse;
use App\FormSent;

class FormRepository
{
	protected $paginate =  20;

	public function __construct()
	{
		$this->paginate = request()->has('per_page') ? request()->per_page : 20;
	}

	public function getCompanyFormsList(Company $company)
	{
		$forms = $company->forms()->select('id','questions','title')->get();
		return $forms;
	}

	public function getCompanyForms(Company $company)
	{
		list($sortName, $sortValue) = parseSearchParam(request());

		$forms = $company->forms()->withCount('responses')->with([ 'user' ]);
		
		if (request()->has('search') && !empty(request()->search)) {
			$search = request()->search;
			$forms = $forms->where(function($query) use ($search){
				$query->where('forms.title', 'like', "%$search%")
					->orWhere('forms.slug','like', "%$search%");
			});
		}
		if(request()->has('sort') && !empty(request()->sort)){
            $forms->orderBy($sortName, $sortValue);
		}

		$forms = $forms->paginate($this->paginate);

		return $forms;
	}
	
	public function getInboundCount(Company $company)
	{
		$forms = $company->forms()->select('id')->pluck('id');
		return FormResponse::whereIn('form_id', $forms)->count();
	}

	public function getOutboundCount(Company $company)
	{
		$forms = $company->forms()->select('id')->pluck('id');
		return FormSent::whereIn('form_id', $forms)->count();
	}

}