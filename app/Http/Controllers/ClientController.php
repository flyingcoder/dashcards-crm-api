<?php

namespace App\Http\Controllers;

use App\Company;
use App\Rules\CollectionUnique;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Kodeine\Acl\Models\Eloquent\Role;

class ClientController extends Controller
{
    private $paginate = 10;

    public function index()
    {
        $company = Auth::user()->company();
        if(request()->has('all') && request()->all ) {
            return $company->clients()->get();
        }
        
        return $company->paginatedCompanyClients();
    }

    public function client($id)
    {
        $client = User::findOrFail($id);
        $client->getAllMeta();

        $invoices = $client->invoices;
        $client->no_invoices = $invoices->count() ?? 0;
        $client->total_amount_paid = 0; //todo fetch total amount paid by this client
        $client->company_name = $client->getMeta('company_name', '');
        $client->contact_name = $client->getMeta('contact_name', '');
        $client->status = $client->getMeta('status', 'Active');

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

            $image_url = env('APP_URL').'/img/members/alfred.png';

            $member = User::create([
                'username' => $username.rand(0,20),
                'last_name' => request()->last_name,
                'first_name' => request()->first_name,
                'email' => request()->email,
                'telephone' => request()->telephone, 
                'job_title' => request()->job_title,
                'password' => bcrypt(request()->password),
                'image_url' => $image_url,
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

        $image_url = env('APP_URL').'/img/members/alfred.png';

        $client = User::create([
            'username' => $username,
            'last_name' => request()->last_name,
            'first_name' => request()->first_name,
            'email' => request()->email,
            'telephone' => request()->telephone, 
            'job_title' => 'Client',
            'password' => bcrypt(request()->password),
            'image_url' => $image_url
        ]);

        $client->setMeta('company_name', request()->company_name);
        if(request()->has('location'))
            $client->setMeta('location', request()->location);
        if(request()->has('contact_name'))
            $client->setMeta('contact_name', request()->contact_name);
        
        // $client->setMeta('company_email', request()->company_email); //Commented by: Dustin 04-20-2018 //No data in form
        // $client->setMeta('company_tel', request()->company_tel); //Commented by: Dustin 04-20-2018 //No data in form
        $client->setMeta('status', request()->status ?? 'Active');
        $client->setMeta('created_by', Auth::user()->id);

        $company = Auth::user()->company();

        $team = $company->teams()
                        ->where('slug', 'client-'.$company->id)
                        ->first();
        if($team)
            $team->members()->attach($client);

        $client->assignRole('client');

        $client['status'] = request()->status;
        $client['company_name'] = request()->company_name; 
        $client['location'] = request()->location;
        $client['contact_name'] = request()->contact_name; 

        return $client;
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
            'email' => [
                'required',
                 Rule::unique('users')->ignore($client->id)
             ],
            'telephone' => 'required',
            'status' => 'required',
            'company_name' => 'required',
        ]);

        $client->first_name = request()->first_name;
        //$client->username = request()->name;
        $client->last_name = request()->last_name;
        $client->email = request()->email;
        $client->telephone = request()->telephone;
        if(request()->has('password'))
            $client->password = request()->password;

        $client->setMeta('company_name', request()->company_name);
        $client->setMeta('status', request()->status);
        if(request()->has('location'))
            $client->setMeta('location', request()->location);
        if(request()->has('contact_name'))
            $client->setMeta('contact_name', request()->contact_name);
        
        $client->save();

        $client['status'] = request()->status ?? 'Active';
        $client['company_name'] = request()->company_name ?? '';
        $client['location'] = request()->location ?? '';
        $client['contact_name'] = request()->contact_name ?? '';

        return $client;
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
