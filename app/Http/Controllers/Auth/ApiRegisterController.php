<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Company;
use App\Team;
use App\Dashboard;
use App\Events\UsersPresence;
use Kodeine\Acl\Models\Eloquent\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use Exception;

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

    protected function create(Request $request)
    {
        //will add the validation later not required
        
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|string|email|max:255|unique:companies,email',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6'
        ]);

        try {

            $company = Company::create([
                'name' => $request->company_name,
                'email' => $request->company_email,
            ]);

            $user = User::create([
               'username' => explode('@', $request->email)[0],
               'first_name' => $request->first_name,
               'last_name' => $request->last_name,
               'image_url' => 'img/members/alfred.png',
               'email' => $request->email,
               'job_title' => 'Administrator',
               'password' => bcrypt($request->password),
            ]);

            $user->assignRole('default-admin-'.$company->id); //prone to change

            $default_team = $company->teams()->first();

            $default_team->members()->attach($user);

            $userObject = $user->scopeDefaultColumn();

            $userObject->company_id = $company->id;

            $user->is_online = 1;

            $user->save();

            UsersPresence::dispatch($user);

            return response()->json([
                'token' => $user->createToken('MyApp')->accessToken, 
                'user' => $userObject
            ], 200);

         } catch (Exception $e) {
            $error_code = $e->errorInfo[1];
            switch ($error_code) {
                case 1062:
                    return response()->json([
                                'error' => 'The company email you have entered is already registered.'
                           ], 500);
                    break;
                
                default:
                    return response()->json([
                                'error' => $e
                           ], 500);
                    break;
            }
         }
    }
}