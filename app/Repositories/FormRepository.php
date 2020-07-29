<?php

namespace App\Repositories;
use App\Company;
use App\FormResponse;
use App\FormSent;

class FormRepository
{
	protected $paginate =  20;

    /**
     * FormRepository constructor.
     */
    public function __construct()
	{
		$this->paginate = request()->has('per_page') ? request()->per_page : 20;
	}

    /**
     * @param Company $company
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCompanyFormsList(Company $company)
	{
        return $company->forms()->select('id','questions','title')->get();
	}

    /**
     * @param Company $company
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Relations\HasMany
     */
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

    /**
     * @param Company $company
     * @return mixed
     */
    public function getInboundCount(Company $company)
	{
		$forms = $company->forms()->select('id')->pluck('id');
		return FormResponse::whereIn('form_id', $forms)->count();
	}

    /**
     * @param Company $company
     * @return mixed
     */
    public function getOutboundCount(Company $company)
	{
		$forms = $company->forms()->select('id')->pluck('id');
		return FormSent::whereIn('form_id', $forms)->count();
	}

}