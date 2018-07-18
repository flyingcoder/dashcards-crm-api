<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Company;
use App\Team;
use App\Dashboard;
use Kodeine\Acl\Models\Eloquent\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use Illuminate\Http\Request;

class ApiRegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|string|email|max:255|unique:companies',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            //'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
    }

    protected function create(Request $request)
    {

        $company = Company::create([
            'name' => $request->company_name,
            'email' => $request->company_email,
        ]);

        $dashboard = $company->dashboards()->create([
            'title' => $request->company_name,
            'description' => $request->company_name.' Dashboard'
        ]);

        $role = new Role();

        $roleAdmin = $role->create(
            [
                'name' => 'Administrator',
                'slug' => 'admin-'.$company->id,
                'description' => 'manage administration privileges',
            ]
        );

        $roleClient = $role->create(
            [
                'name' => 'Client',
                'slug' => 'client-'.$company->id,
                'description' => 'Client privileges',
            ]
        );

        $roleManager = $role->create(
            [
                'name' => 'Manager',
                'slug' => 'manager-'.$company->id,
                'description' => 'manage a team privileges',
            ]
        );

        $company->roles()->attach([
            $roleAdmin->id,
            $roleClient->id,
            $roleManager->id
        ]);

        $team = Team::create([
            'name' => 'Admin Team',
            'company_id' => $company->id,
            'description' => 'This is the default team for a company'
        ]);

	    $user = User::create([
           'username' => explode('@', $request->email)[0],
           'first_name' => $request->first_name,
           'last_name' => $request->last_name,
	       'image_url' => 'img/members/alfred.png',
	       'email' => $request->email,
	       'password' => bcrypt($request->password),
        ]);

        $user->assignRole('admin'); //prone to change

        $company->teams()->save($team);

        $success['token'] =  $user->createToken('MyApp')->accessToken;

        return response()->json([
            'success' => $success, 
            'user' => $user 
        ], 200);
    }
}