<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Policies\UserPolicy;

class UserController extends Controller
{

    public function user()
    {
        return request()->user();
    }

    public function store(Request $request)
    {
        (new UserPolicy())->create();

    	$validated = $request->validate([
    		'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'group' => 'integer|exist:role,id',
            'job_title' => 'string',
            'telephone' => 'string',
            'password' => 'required|string|min:6|confirmed',
    	]);

        $user = User::create(request()->all());

        return $user;

    }

    /*
    public function clients(Request $request)
    {
        $clients = auth()->user()->paginatedClients();

        if(request()->has('all') && request()->all == true)
            $clients = auth()->user()->clients()->get();

        return $clients;
    }*/

    public function getMeta($key)
    {
        $company = auth()->user()->company();

        return $company->getMeta($key);
    }

    public function addPaypalDetails()
    {
        $company = auth()->user()->company();

        $company->setMeta('paypal-details', [
            'gateway_active' => request()->gateway_active,
            'paypal_email_address' => request()->paypal_email_address,
            'currency' => request()->currency,
            'paypal_ipn' => request()->paypal_ipn,
        ]);

        return $company;
    }

    public function addBankTransferDetails()
    {
        $company = auth()->user()->company();

        $company->setMeta('bank-transfer-details', [
            'gateway_active' => request()->gateway_active,
            'payment_instructions' => request()->payment_instructions
        ]);

        return $company;
    }

    public function addInvoiceSettings()
    {
        $company = auth()->user()->company();

        $company->setMeta('invoice-settings', [
            'allow_partial_payment' => request()->allow_partial_payment,
            'email_overdue_reminder' => request()->email_overdue_reminder,
            'notes' => request()->notes
        ]);

        return $company;
    }

    public function addCompanyDetails()
    {
        $company = auth()->user()->company();

        $company->setMeta('details', [
            'address_line' => request()->address_line,
            'city' => request()->city,
            'state' => request()->state,
            'zip_code' => request()->zip_code,
            'country' => request()->country,
            'telephone' => request()->telephone,
            'from_name' => request()->from_name,
            'email_signature' => request()->email_signature
        ]);

        return $company;
    }

    public function projects()
    {
        return auth()->user()->userPaginatedProject(request());
    }

    public function countTasks()
    {
        return User::findOrFail(request()->user()->id)->tasks->count();
    }

    public function tasks()
    {
        return User::findOrFail(request()->user()->id)->tasks;
    }

    public function show(Request $request)
    {
        (new UserPolicy())->show(User::findOrFail($request->id));

        if($request->ajax())
            return response()->json(User::findOrFail($request->id));
        
        return view('user.profile', ['user' => User::findOrFail($request->id)]);
    }

    public function index(Request $request)
    {
        $per_page = isset($request->per_page) ? $request->per_page : 15;
        if($request->ajax())
            return response()->json(User::all()->paginate($per_page));
        return view('user.index', ['user' => User::all()->paginate($per_page)]);
    }
}
