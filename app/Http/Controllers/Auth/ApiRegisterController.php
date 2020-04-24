<?php

namespace App\Http\Controllers\Auth;

use App\Company;
use App\Dashboard;
use App\Events\UsersPresence;
use App\Http\Controllers\Controller;
use App\Team;
use App\User;
use Exception;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Kodeine\Acl\Models\Eloquent\Role;

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
            //'company_email' => 'required|string|email|max:255|unique:companies,email',
            'company_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6'
        ]);

        try {
            DB::beginTransaction();

            $company = Company::create([
                'name' => $request->company_name,
                // 'email' => $request->email,
            ]);

            $user = User::create([
               'username' => explode('@', $request->email)[0],
               'first_name' => $request->first_name,
               'last_name' => $request->last_name,
               'image_url' => random_avatar(),
               'email' => $request->email,
               'job_title' => 'Super Administrator',
               'password' => bcrypt($request->password),
               'is_online' => 1
            ]);

            $default_team = $company->teams()->create([
                'name' => $company->name.' Default Team',
                'company_id' => $company->id,
                'slug' => 'default-'.$company->id,
                'description' => 'This is the default team for '. $company->name
            ]);

            $user->assignRole('admin');

            $default_team->members()->attach($user);

            DB::commit();

            $userObject = $user->fresh();
            $userObject->company_id = $company->id;
            $userObject->is_company_owner = true;
            $userObject->is_admin = true;
            $userObject->role = $userObject->userRole();
            $userObject->can = $userObject->getPermissions();
            $userObject->company = $company;

            UsersPresence::dispatch($userObject);

            return response()->json([
                'token' => $userObject->createToken('MyApp')->accessToken, 
                'user' => $userObject
            ], 200);

         } catch (Exception $e) {
            DB::rollback();
            return response()->json([ 'message' => $e->getMessage() ], 500);
        }
    }


    public function getUserId(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $user = User::where('code', $request->code)->first();

        if (!$user) {
            return response()->json([ 'error' => 'Invalid code' ], 500);
        }

        return response()->json([ 'user_id' => $user->id ], 200);
    }

    public function setPassword(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'user_id' => 'required',
            'password' => 'required|string|min:6'
        ]);

        $user = User::where('code', $request->code)->where('id', $request->user_id)->first();
        
        if (!$user) {
            return response()->json([ 'error' => 'This user code was already expired.' ], 500);
        }

        $user->password = bcrypt($request->password);
        $user->code = null; //set to null so it cant be used again
        $user->save();

        return response()->json([ 'user' => $user ], 200);
    }
}