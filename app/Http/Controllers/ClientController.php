<?php

namespace App\Http\Controllers;

use App\Company;
use App\Repositories\InvoiceRepository;
use App\Repositories\MembersRepository;
use App\Rules\CollectionUnique;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Kodeine\Acl\Models\Eloquent\Role;

class ClientController extends Controller
{
    private $paginate = 10;

    protected $repo;
    protected $mrepo;

    public function __construct(InvoiceRepository $repo, MembersRepository $mrepo)
    {
        $this->repo = $repo;
        $this->mrepo = $mrepo;
    }

    public function index()
    {
        $company = Auth::user()->company();
        $this->mrepo->setCompany($company);
        if(request()->has('all') && request()->all ) {
            $clients = $this->mrepo->getUsersByType('clients', [], false);
            foreach ($clients as $key => $client) {
                $clients[$key]->company = Company::find($client->props['company_id'] ?? null);
            }
            return $clients;
        }

        $clients = $this->mrepo->getUsersByType('clients', [], true);

        $items = $clients->getCollection();
        $data = collect([]);
        foreach ($items as $key => $client) {
            $client->is_client = true;
            $client->projects = $client->projects()->count();
            $company = Company::find($client->props['company_id'] ?? null);
            $data->push(array_merge($client->toArray(), ['company' =>  $company ]));   
        }
        $clients->setCollection($data);

        return $clients;
    }

    public function client($id)
    {
        $client = User::findOrFail($id);
        $client->getAllMeta();
        $client->company = Company::find($client->props['company_id']);
        $client->no_invoices = $this->repo->countInvoices($client,'all');
        $client->total_amount_paid = $this->repo->totalInvoices($client,'billed_to'); 

        return $client;
    }

    public function addStaffs($id)
    {
        try {

            request()->validate([
                'last_name' => 'required|string',
                'first_name' => 'required|string',
                'email' => 'required|email|unique:users',
                'telephone' => 'required',
                'password' => 'required|confirmed'
            ]);

            $username = explode('@', request()->email)[0];

            $member = User::create([
                'username' => $username.rand(0,20),
                'last_name' => request()->last_name,
                'first_name' => request()->first_name,
                'email' => request()->email,
                'telephone' => request()->telephone, 
                'job_title' => request()->job_title,
                'password' => bcrypt(request()->password),
                'image_url' => random_avatar(null),
                'created_by' => $id
            ]);

            $company = auth()->user()->company();
            
            $team = $company->clientStaffTeam();

            $role = 'client';

            $team->members()->attach($member);

            $member->assignRole($role);

            $member->group_name = $role;

            //\Mail::to($member)->send(new UserCredentials($member, request()->password));

            return $member->load('projects', 'tasks');

        } catch (Exception $e) {

            $error_code = $e->errorInfo[1];

            switch ($error_code) {
                case 1062:
                    return response()->json([
                                'error' => 'The company email you have entered is already registered.'
                           ], 500);
                    break;
                case 1048:
                    return response()->json([
                                'error' => 'Some fields are missing.'
                           ], 500);
                default:
                    return response()->json([
                                'error' => $e."test"
                           ], 500);
                    break;
            }
        }
    }

    public function tasks($id)
    {
        $client = User::findOrFail($id);

        return $client->tasks()->get();
    }

    public function staffs($id)
    {
        $client = User::findOrFail($id);

        return $client->clientStaffs();
    }

    public function invoices($id)
    {
        $client = User::findOrFail($id);

        return $client->invoices()->get();
    }

    public function details($id)
    {
        return view('pages.client-details');
    }

    public function save()
    {
        return view('pages.clients-new', [
            'action' => 'add'
        ]);
    }

    public function store()
    {
        request()->validate([
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'telephone' => 'required', 
            'password' => 'required',
            'status' => 'required',
            'company_name' => 'required',
        ]);

        $username = explode('@', request()->email)[0];
        try {
            DB::beginTransaction();

            $client_company = Company::create([
                    'name' => request()->company_name,
                    'is_private' => 1,
                    'address' => request()->location ?? null,
                    'created_at' => now()->format('Y-m-d H:i:s'),
                    'others' => [
                            'contact_name' => request()->contact_name ?? null
                        ]
                ]);
            $client = User::create([
                    'username' => $username,
                    'last_name' => request()->last_name,
                    'first_name' => request()->first_name,
                    'email' => request()->email,
                    'telephone' => request()->telephone, 
                    'job_title' => 'Client',
                    'password' => bcrypt(request()->password),
                    'image_url' => random_avatar('neutral'),
                    'created_by' => Auth::user()->id,
                    'props' => [
                            'company_id' => $client_company->id,
                            'status' => request()->status ?? 'Active'
                        ]
                ]);

            $company = Auth::user()->company();
            $team = $company->teams()->where('slug', 'client-'.$company->id)->first();
            if($team){
                $team->members()->attach($client);
            }

            $client->assignRole('client');

            DB::commit();

            $client->company = $client_company;

            return $client;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function updatePicture($id)
    {
        $client = User::findOrFail($id);

        //(new UserPolicy())->update($model);

        $file = request()->file('file');
        
        $model = User::findOrFail($id);

        $media = $model->addMedia($file)
                        ->usingFileName('profile-'.$model->id.".png")
                        ->toMediaCollection('avatars');

        $model->image_url = url($media->getUrl('thumb'));

        $model->save();

        return $model;
    }

    public function update($id)
    {
        $client = User::findOrFail($id);
        request()->validate([
            //'username' => 'required|string',
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'email' => [ 'required', Rule::unique('users')->ignore($client->id) ],
            'telephone' => 'required',
            'status' => 'required',
            'company_name' => 'required',
        ]);
        try {
            DB::beginTransaction();

            $client->first_name = request()->first_name;
            $client->last_name = request()->last_name;
            $client->email = request()->email;
            $client->telephone = request()->telephone;
            if(request()->has('password')){
                $client->password = request()->password;
            }
            $props = $client->props;
            $props['status'] = request()->status ?? 'Active';
            $props['updated_by'] = Auth::user()->id;
            $client->props = $props;
            $client->save();

            $company = Company::findOrFail($client->props['company_id'] ?? null);
            $company->name = request()->company_name;
            $company->address = request()->location ?? null;
            $props = $company->others;
            $props['contact_name'] = request()->contact_name ?? null;
            $company->others = $props;
            $company->save();

            $client->company = $company;

            return $client;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        $client = User::findOrFail($id);

        if($client->projects()->count() != 0)
            abort(401, "This client has open project please delete the project first.");

        if($client->delete()){
            return response()->json(['message' => 'Client successfully deleted'], 200);
        } else {
            return response()->json(['message' => "Client can't be deleted"], 500);
        }
        
    }

    public function bulkDelete()
    {
        request()->validate([
            'ids' => 'required|array'
        ]);

        try {
            DB::beginTransaction();
            $clients = User::whereIn('id', request()->ids)->with('projects')->get();
            
            if ($clients) {
                foreach ($clients as $key => $client) {
                    if ($client->projects->count() != 0) {
                        throw new \Exception(" Client {$client->fullname} has an open project, please delete the project first.", 1);
                    } 
                    $client->delete();
                }
            }
            
            DB::commit();
            return response()->json(['message' => $clients->count().' client(s) was successfully deleted'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => "Some client has open project please delete the project first"], 500);
        }
    }

}
