<?php

namespace App\Http\Controllers;

use App\Policies\ServicePolicy;
use App\ServiceList;
use Illuminate\Http\Request;

class ServiceListController extends Controller
{
	protected $paginate = 12;

    public function index()
    {
    	$services = ServiceList::where('status', 'active')->withCount('campaigns')->with('creator');

    	if (request()->has('all') && request()->all) {
    		return $services->get();
    	}

    	return  $services->paginate(request()->per_page ?? $this->paginate);
    }

    public function list()
    {
        return ServiceList::where('status', 'active')->get();
    }

    public function service($id)
    {
    	$service = ServiceList::findOrFail($id);

    	return $service;
    }

    public function store()
    {   
        (new ServicePolicy())->create();

    	request()->validate([
    		'names' => 'required|array',
    		'description' => 'sometimes|string',
    		'icon' => 'sometimes|url',
    		'status' => 'sometimes|string'
    	]);

    	$company = auth()->user()->company();
        $added = [];
        foreach (request()->names as $name) {
    	   $service = $company->servicesList()->create([
    			'name' => $name,
    			'description' => request()->description ?? null,
    			'icon' => request()->icon ?? null,
    			'status' => request()->status ?? 'active',
    			'created_at' => now()->format('Y-m-d H:i:s'),
    			'user_id' => auth()->user()->id
    		]);
            $service->load('creator');
            $service->campaigns_count = 0;
            $added[] = $service;
        }

    	return $added;
    }

    public function update($id)
    {
    	request()->validate([
    		'name' => 'required|string',
    		'description' => 'sometimes|string',
    		'icon' => 'sometimes|url',
    		'status' => 'sometimes|string'
    	]);

    	$service = ServiceList::findOrFail($id);

        (new ServicePolicy())->update();

    	$service->name = request()->name;
        if (request()->has('description')) {
    		$service->description = request()->description ?? null;
        }
        if (request()->has('icon')) {
    		$service->icon = request()->icon ?? null;
        }
        if (request()->has('status')) {
            $service->status = request()->status ?? 'active';
        }
		$service->save();
        $service->load('creator');
        $service->campaigns_count = $service->campaigns()->count();

		return $service;
    }

    public function delete($id)
    {
    	$service = ServiceList::findOrFail($id);

        (new ServicePolicy())->delete($service);

    	$service->delete();

    	return response()->json(['message' => 'Succesfully deleted'], 200);
    }
}
