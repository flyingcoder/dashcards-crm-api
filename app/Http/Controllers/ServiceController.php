<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Validator;
use App\Team;
use App\Service;
use App\Company;
use Illuminate\Http\Request;
use App\Policies\ServicePolicy;
use App\Rules\CollectionUnique;
use Illuminate\Pagination\LengthAwarePaginator;

class ServiceController extends Controller
{
    private $paginate = 5;

    public function index()
    {  
        /*
        $page = request()->input('page', 1);

        $offSet = ($page * $this->paginate) - $this->paginate;  
        $itemsForCurrentPage = array_slice($result, $offSet, $this->paginate, true);  
        $result = new LengthAwarePaginator($itemsForCurrentPage, count($result), $this->paginate, $page);
        */
        (new ServicePolicy())->index();

        if(!request()->ajax())
            return view('pages.services');

        $company = Auth::user()->company();

        $result = $company->paginatedCompanyServices(request());

        if(request()->has('all') && request()->all == true)
            $result = $company->servicesList();

        return $result;

        
    }

    public function save()
    {
        (new ServicePolicy())->create();

        return view('includes.add-service-modal', [
            'action' => 'add'
        ]);
    }


    public function isValid(){

        $company = Auth::user()->company();

        request()->validate([
            'name' => [
                'required',
                'string',
                new CollectionUnique($company->servicesNameList())
            ]
        ]);

        return response(200);
    }

    public function store(Request $request)
    {
        try{
            
            $services = $request->all();

            $res = [];

            (new ServicePolicy())->create();

            foreach($services as $s){
                $service = Service::create([
                            'user_id' => Auth::user()->id,
                            'name' => $s['name'] 
                        ]);

                $created_at = $service->created_at->toDateTimeString();

                $res[] = collect([
                    'name' => ucfirst(Auth::user()->last_name).', '.ucfirst(Auth::user()->first_name),
                    'service_name' => $service->name,
                    'service_created_at' => $created_at
                ]);
            }
            
            return $res;

        } catch (\Exception $ex) {

            return response(['message' => $ex->getMessage()], 500);

        }
        
    }

    public function getService($id)
    {
        $service = Service::findOrFail($id);

        // (new ServicePolicy())->view($service);

        return $service;
    }

    public function update($id)
    {
        $service = Service::findOrFail($id);
        $company = Auth::user()->company();

        (new ServicePolicy())->update($service);

        request()->validate([
            'name' => [
                'required',
                'string',
                new CollectionUnique($company->servicesNameList())
            ]
        ]);

        $service->name = request()->name;
        
        $service->save();

        return collect([
            'name' => ucfirst($service->user->last_name).', '.ucfirst($service->user->first_name),
            'service_name' => $service->name,
            'service_created_at' => $service->created_at->toDateTimeString()
        ]);
    }

    public function delete($id)
    {
        $service = Service::findOrFail($id);

        (new ServicePolicy())->delete($service);

        return $service->destroy($id);
    }


}
