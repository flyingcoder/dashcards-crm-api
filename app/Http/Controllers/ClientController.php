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

        return $result;
    }

    public function client($id)
    {
        return User::findOrFail($id);
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
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'telephone' => 'required',
            'password' => 'required|confirmed',
            'status' => 'required',
            'company_name' => 'required',
            'company_email' => 'required',
            'company_tel' => 'required'
        ]);

        $client = User::create([
            'name' => request()->name,
            'email' => request()->email,
            'telephone' => request()->telephone,
            'job_title' => 'Client',
            'password' => bcrypt(request()->password),
            'image_url' => 'img/members/alfred.png'
        ]);

        $client->setMeta('company_name', request()->company_name);
        $client->setMeta('company_email', request()->company_email);
        $client->setMeta('company_tel', request()->company_tel);
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

        request()->session()->flash('message.level', 'success');
        request()->session()->flash('message.content', 'User was successfully added!');

        return back();
    }

    public function edit($id)
    {
        $client = User::findOrFail($id);

         return view('pages.clients-new', [
             'client' => $client,
             'action' => 'edit'
             ]);
    }

    public function update($id)
    {
        $client = User::findOrFail($id);

        request()->validate([
            'name' => 'required|string',
            'email' => [
                'required',
                 Rule::unique('users')->ignore($client->id)
             ],
            'telephone' => 'required',
            'job_title' => 'required',
            'password' => 'required|confirmed',
            'status' => 'required',
            'company_name' => 'required',
            'company_email' => 'required',
            'company_tel' => 'required'
        ]);

        $client->name = request()->name;
        $client->email = request()->email;
        $client->telephone = request()->telephone;
        $client->job_title = request()->job_title;
        if(!empty(request()->password))
            $client->password = request()->password;
        
        $client->save();

        $client->setMeta('company_name', request()->company_name);
        $client->setMeta('company_email', request()->company_email);
        $client->setMeta('company_tel', request()->company_tel);
        $client->setMeta('status', request()->status);

        request()->session()->flash('message.level', 'success');
        request()->session()->flash('message.content', 'User was successfully updated!');

        return back();
    }

    public function delete($id)
    {
        $client = User::findOrFail($id);
        return $client->destroy($id);
    }

}
