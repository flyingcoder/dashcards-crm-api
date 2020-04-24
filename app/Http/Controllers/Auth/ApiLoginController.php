<?php

namespace App\Http\Controllers\Auth;

use App\Events\UsersPresence;
use App\Http\Controllers\Controller;
use App\Timer;
use Auth;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class ApiLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;


    public $successStatus = 200;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login()
    {
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            
            $user = Auth::user();
            $user->is_online = 1;
            $user->save();

            $userObject = $user->fresh();
            $userObject->company_id = $user->company()->id;
            $userObject->company = $user->company();
            $userObject->is_admin = $user->hasRole('admin');
            $userObject->role = $user->userRole();
            $userObject->can = $user->getPermissions();
            $userObject->is_company_owner = $user->getIsCompanyOwnerAttribute();

            UsersPresence::dispatch($userObject);

            return response()->json([
                'token' => $user->createToken('MyApp')->accessToken, 
                'user' => $userObject
            ], $this->successStatus);
        } 
        else{ 
            return response()->json(['message' => 'Invalid email or password!'], 401); 
        } 
    }

    public function logout()
    {
        $user = auth()->user();

        if(request()->has('user_id')) {
            if($user->id != response()->user_id)
                $user = User::findOrFail(response()->user_id); 
        }

        if (Auth::check()) {
           //force stop global timer for the user upon logout
           $timer = $user->lastTimer();

           if ($timer && $timer->action == 'start') {
                $closetimer  = Timer::create([
                    'company_id' => $user->company()->id,
                    'timer_name' => $user->first_name.' Timer',
                    'description' => 'Force stop timer by logout',
                    'subject_id' => $user->company()->id,
                    'subject_type' => 'App\\Company',
                    'causer_id' => $user->id,
                    'causer_type' => 'App\\User',
                    'action' => 'stop',
                    'status' => 'close'
                ]);

                $start = Carbon::parse($timer->created_at);
                $end = Carbon::now();
                $total_sec = $end->diffInSeconds($start);
                $args = [
                            'total_time' => gmdate("H:i:s", $total_sec),
                            'total_seconds' => $total_sec
                        ];
                $closetimer->update(['properties' => $args]);
           }

           $user->is_online = 0;

           $user->save();

           Auth::user()->AauthAcessToken()->delete();

           UsersPresence::dispatch($user);

           return $user;
        }
        
    }

    /*
    public function login(Request $request) {

        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $request->merge([$field => $request->login]);

        if (auth()->attempt($request->only($field, 'password')))
        {
            UserLogin::dispatch(auth()->user());
            return redirect($this->redirectTo);
        }

        return redirect('/login')->withErrors([
            'error' => 'These credentials do not match our records.',
        ]);
    }*/
}
