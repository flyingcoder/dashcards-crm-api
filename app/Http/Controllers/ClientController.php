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

class ClientController extends Controller
{
    private $paginate = 5;

    public function index()
    {
        if(!request()->ajax())
            return view('pages.clients');

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
            'password' => 'required', //Remove Confirm Handled in frontend by: Dustin 04-20-2018
            'status' => 'required',
            'company_name' => 'required',
            // 'company_email' => 'required', //Commented by: Dustin 04-20-2018 //No data in form
            // 'company_tel' => 'required' //Commented by: Dustin 04-20-2018 //No data in form
        ]);

        $username = explode('@', request()->email)[0];

        $client = User::create([
            'username' => $username,
            'last_name' => request()->last_name,
            'first_name' => request()->first_name,
            'email' => request()->email,
            'telephone' => request()->telephone, 
            'job_title' => 'Client',
            'password' => bcrypt(request()->password),
            'image_url' => 'img/members/alfred.png'
        ]);

        $client->setMeta('company_name', request()->company_name);
        // $client->setMeta('company_email', request()->company_email); //Commented by: Dustin 04-20-2018 //No data in form
        // $client->setMeta('company_tel', request()->company_tel); //Commented by: Dustin 04-20-2018 //No data in form
        $client->setMeta('status', request()->status);
        $client->setMeta('created_by', Auth::user()->id);

        $company = Auth::user()->company();

        $team = $company->teams()
                ->where('slug', 'clients')
                ->count();

        if(!$team) {
            $company->teams()->create([
                'name' => 'Clients',
                'description' => 'Clients of '.$company->name,
                'slug' => 'clients'
            ]);
        }

        $team = $company->teams()
                ->where('slug', 'clients')
                ->first();

        $team->members()->attach($client);

        $client->assignRole('client');
        
        // Commented unable to use session in API
        // request()->session()->flash('message.level', 'success'); 
        // request()->session()->flash('message.content', 'User was successfully added!');

        // Commented need to newly added client return instead.
        // return back();

        return $client;
    }

    public function update($id)
    {
        $client = User::findOrFail($id);

        request()->validate([
            'username' => 'required|string',
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'email' => [
                'required',
                 Rule::unique('users')->ignore($client->id)
             ],
            'telephone' => 'required',
            'status' => 'required',
            'company_name' => 'required',
            'company_email' => 'required',
            'company_tel' => 'required'
        ]);

        $client->first_name = request()->name;
        $client->username = request()->name;
        $client->last_name = request()->name;
        $client->email = request()->email;
        $client->telephone = request()->telephone;
        if(request()->has('password'))
            $client->password = request()->password;
        
        $client->save();

        $client->setMeta('company_name', request()->company_name);
        $client->setMeta('company_email', request()->company_email);
        $client->setMeta('company_tel', request()->company_tel);
        $client->setMeta('status', request()->status);

        //request()->session()->flash('message.level', 'success');
        //request()->session()->flash('message.content', 'User was successfully updated!');

        return $client;
    }

    public function delete($id)
    {
        // updated by dustin 09-20-2018 handle failed delete
        $client = User::findOrFail($id);
        if($client->destroy($id)){
            return response('success', 200);
        }
        else {
            return response('failed', 500);
        }
        
    }

}
