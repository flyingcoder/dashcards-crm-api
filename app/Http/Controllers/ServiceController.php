<?php

namespace App\Http\Controllers;

use App\Company;
use App\Policies\ServicePolicy;
use App\Repositories\ServiceRepository;
use App\Rules\CollectionUnique;
use App\Service;
use App\Team;
use Auth;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Validator;

class ServiceController extends Controller
{
    private $paginate = 20;

    protected $srepo;

    public function __construct(ServiceRepository $srepo)
    {
        $this->srepo = $srepo;
    }

    public function service($id)
    {
        $service = Service::findOrFail($id);
        $service->load(['managers', 'client', 'members']);
        $service->total_time = '';//$project->totalTime();

        if (request()->has('for') && request()->for == 'invoice') {
            $service->client_name = $service->client[0]->fullname;
            $service->billed_to = $service->client[0]->fullname;

            $service->manager_name = '';
            $service->billed_from = '';
                
            $service->location = $service->props['location'] ?? '';
            $service->company_name = $service->business_name;
        }

        return $service;
    }

    public function index()
    {  
        (new ServicePolicy())->index();

        $company = Auth::user()->company();

        $result = $this->srepo->getCompanyServices($company);

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
        request()->validate([
            'name' => 'required|min:5',
            'description' => 'required|min:5',
            'started_at' => 'required|date',
            'client_id' => 'required|exists:users,id',
            'business_name' => 'required|min:5'
        ]);

        try{ 
            DB::beginTransaction();    
            $user = auth()->user();
            $service = $user->company()->services()->create([
                    'type' => 'service',
                    'title' => trim(request()->name),
                    'description' => request()->description ?? null,
                    'created_at' => now()->format('Y-m-d H:i:s'),
                    'status' => request()->status ?? 'Active',
                    'started_at' => request()->started_at ?? now()->format('Y-m-d'),
                    'end_at' => request()->end_at ?? null,
                    'props' => [
                            'creator' =>  $user->id,
                            'business_name' => request()->business_name,
                            'location' => request()->location ?? null,
                            'icon' => request()->icon ?? null,
                        ]
                ]); 

            if(request()->has('extra_fields') && !empty(request()->extra_fields)){
                $service->setMeta('extra_fields', request()->extra_fields);
            }
            
            $service->team()->attach(request()->client_id, ['role' => 'Client']);

            if(request()->has('members')){
                foreach (request()->members as $value) {
                    $service->team()->attach($value, ['role' => 'Members']);
                }
            }
            
            if(request()->has('managers')){
                foreach (request()->managers as $value) {
                    $service->team()->attach($value, ['role' => 'Manager']);
                }
            }

            DB::commit();

            $service->load([ 'managers', 'client', 'members' ]);
            
            return response()->json($service, 201);
        } catch (\Exception $ex) {
            DB::rollback();
            return response(['message' => $ex->getMessage()], 500);
        }   
    }

    public function update($id)
    {

        $service = Service::findOrFail($id);
        $company = Auth::user()->company();

        (new ServicePolicy())->update($service);

        request()->validate([
            'name' => 'required|min:5',
            'description' => 'required|min:5',
            'started_at' => 'required|date',
            'client_id' => 'required|exists:users,id',
            'business_name' => 'required|min:5'
        ]);

        try{ 
            DB::beginTransaction();    

            $service->title = trim(request()->name);
            $service->description = request()->description ?? null;
            $service->status = request()->status ?? 'Active';
            $service->started_at = request()->started_at ?? now()->format('Y-m-d');
            $service->end_at = request()->end_at ?? null;

            $props = $service->props;
            $props['location'] = request()->location ?? null;
            $props['business_name'] = request()->business_name ?? null;
            $props['icon'] = request()->icon ?? null;

            $service->props = $props;
            $service->save();

            if(request()->has('extra_fields') && !empty(request()->extra_fields)){
                $service->setMeta('extra_fields', request()->extra_fields);
            }
            $service->team()->detach();

            $service->team()->attach(request()->client_id, ['role' => 'Client']);

            if(request()->has('members')){
                foreach (request()->members as $value) {
                    $service->team()->attach($value, ['role' => 'Members']);
                }
            }
            
            if(request()->has('managers')){
                foreach (request()->managers as $value) {
                    $service->team()->attach($value, ['role' => 'Manager']);
                }
            }

            DB::commit();

            $service->load([ 'managers', 'client', 'members' ]);
            
            return response()->json($service, 200);
        } catch (\Exception $ex) {
            DB::rollback();
            return response(['message' => $ex->getMessage()], 500);
        }
    }

    public function bulkDelete()
    {
        request()->validate([
            'ids' => 'required|array'
        ]);
        
        try {

            $service = new Service();

            $service->whereIn('id', request()->ids)->delete();

            return response(['message' => 'Successfully deleted services.'], 200);

        } catch (\Exception $ex) {

            return response(['message' => 'Services deletion failed'], 500);

        }
        
    }

    public function delete($id)
    {
        $service = Service::findOrFail($id);

        (new ServicePolicy())->delete($service);

        if($service->delete()){
            return response(['message' => 'Successfully deleted services.'], 200);
        } 
            
        return response(['message' => 'Services deletion failed.'], 500);
    }

}
