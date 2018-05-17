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
            (new ServicePolicy())->create();
            foreach($services as $s){
                Service::create([
                    'user_id' => Auth::user()->id,
                    'name' => $s['name'] 
                ]);    
            }
            
            return response()
                    ->json(['message' => 'Service was successfully added.', ]);
        }
        catch (\Exception $ex) {
            return response(['message' => $ex->getMessage()], 500);
        }
        
    }

    public function service($id)
    {
        $service = Service::findOrFail($id)

        (new ServicePolicy())->view($service);

        return $service;
    }

    public function update($id)
    {
        $service = Service::findOrFail($id);

        (new ServicePolicy())->update($service);

        request()->validate([
            'name' => 'required|string'
        ]);

        $service->name = request()->name;
        
        $service->save();

        return $service;
    }

    public function delete($id)
    {
        $service = Service::findOrFail($id);

        (new ServicePolicy())->delete($service);

        return $service->destroy($id);
    }


}
