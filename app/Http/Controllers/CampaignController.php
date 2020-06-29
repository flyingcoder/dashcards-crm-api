<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Company;
use App\Policies\ProjectPolicy;
use App\Repositories\CampaignRepository;
use App\Rules\CollectionUnique;
use App\ServiceList;
use App\Team;
use Auth;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Validator;

class CampaignController extends Controller
{
    private $paginate = 20;

    protected $crepo;

    public function __construct(CampaignRepository $crepo)
    {
        $this->crepo = $crepo;
    }

    public function service($id)
    {
        $service = Campaign::findOrFail($id);
        $service->load(['managers', 'client', 'members', 'service']);
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
        (new ProjectPolicy())->index();

        $company = Auth::user()->company();

        $result = $this->crepo->getCompanyCampaigns($company);

        return $result;
    }

    public function save()
    {
        (new ProjectPolicy())->create();

        return view('includes.add-service-modal', [
            'action' => 'add'
        ]);
    }

    public function isValid(){

        $company = Auth::user()->company();

        request()->validate(['name' => 'required|string|exists:services,name']);

        return response(200);
    }

    public function store(Request $request)
    {
        request()->validate([
            'description' => 'required|min:5',
            'started_at' => 'required|date',
            'client_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'business_name' => 'required|min:5'
        ]);

        try{ 
            DB::beginTransaction();    
            $user = auth()->user();
            $service = ServiceList::findOrFail(request()->service_id);
            $campaign = $user->company()->campaigns()->create([
                    'type' => 'campaign',
                    'service_id' => $service->id,
                    'title' => $service->name,
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
                $campaign->setMeta('extra_fields', request()->extra_fields);
            }
            
            $campaign->team()->attach(request()->client_id, ['role' => 'Client']);

            if(request()->has('members')){
                foreach (request()->members as $value) {
                    $campaign->team()->attach($value, ['role' => 'Members']);
                }
            }
            
            if(request()->has('managers')){
                foreach (request()->managers as $value) {
                    $campaign->team()->attach($value, ['role' => 'Manager']);
                }
            }

            DB::commit();

            $campaign->load([ 'managers', 'client', 'members', 'service' ]);
            
            return response()->json($campaign, 201);
        } catch (\Exception $ex) {
            DB::rollback();
            return response(['message' => $ex->getMessage()], 500);
        }   
    }

    public function update($id)
    {

        $service = Campaign::findOrFail($id);
        $company = Auth::user()->company();

        (new ProjectPolicy())->update($service);

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

            $service = new Campaign();

            $service->whereIn('id', request()->ids)->delete();

            return response(['message' => 'Successfully deleted campaigns.'], 200);

        } catch (\Exception $ex) {

            return response(['message' => 'Campaigns deletion failed'], 500);

        }
        
    }

    public function delete($id)
    {
        $service = Campaign::findOrFail($id);

        (new ProjectPolicy())->delete($service);

        if($service->delete()){
            return response(['message' => 'Successfully deleted campaigns.'], 200);
        } 
            
        return response(['message' => 'Campaign deletion failed.'], 500);
    }

}
