<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\User;
use App\Company;
use Illuminate\Http\Request;
use App\Rules\CollectionUnique;
use Kodeine\Acl\Models\Eloquent\Role;
use Illuminate\Validation\Rule;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    private $paginate = 10;

    public function index()
    {
        $company = Auth::user()->company();

        $result = $company->paginatedCompanyClients(request());

        if(request()->has('all') && request()->all == true)
            $result = $company->clients()->get();

        return $result;
    }

    public function client($id)
    {
        $client = User::findOrFail($id);

        $client->getAllMeta();

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
        // $client->setMeta('company_email', request()->company_email); //Commented by: Dustin 04-20-2018 //No data in form
        // $client->setMeta('company_tel', request()->company_tel); //Commented by: Dustin 04-20-2018 //No data in form
        $client->setMeta('status', request()->status);
        $client->setMeta('created_by', Auth::user()->id);

        $company = Auth::user()->company();

        $team = $company->teams()
                        ->where('slug', 'client-'.$company->id)
                        ->first();

        $team->members()->attach($client);

        $client->assignRole('client');

        $client['status'] =request()->status;
        $client['company_name'] = request()->company_name; 
        $client['location'] = request()->location; 

        return $client;
    }

    public function updatePicture($id)
    {
        $client = User::findOrFail($id);
        
        $path = "";

        if(request()->has('avatar')) {
            $path = request()->file('avatar')->store(
                        'avatars/'.$client->id, 'public'
                    );

            $client->image_url = $path;

            $client->save();

        } else {
            return response(500, 'File is missing');
        }

        return $path;
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
        
        $client->save();

        $client['status'] = $client->getMeta('status');
        $client['company_name'] = $client->getMeta('company_name');

        return $client;
    }

    public function delete($id)
    {
        $client = User::findOrFail($id);

        if($client->projectsCount() != 0)
            abort(401, "This client has open project please delete the project first.");
        
        if($client->delete()){
            return response('success', 200);
        } else {
            return response('failed', 500);
        }
        
    }

}
