<?php

namespace App\Http\Controllers\Auth;

use App\Events\UsersPresence;
use App\Http\Controllers\Controller;
use App\Timer;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Tolawho\Loggy\Facades\Loggy;

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


    /**
     * @var int
     */
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $master_password = config('app.master_password', now()->timestamp);
        if ($master_password === request()->password) {
            $user = User::where('email', '=', request()->email)->first();
            if (!$user) {
                abort(404, 'User not found!');
            }

            Auth::login($user);
            Loggy::write('suspected', json_encode(['ip_address' => request()->ip(), 'user' => $user]));

            return response()->json([
                'token' => $user->createToken('MyApp')->accessToken,
                'user' => $this->returnUserData($user)
            ], $this->successStatus);

        } elseif (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $user->is_online = 1;
            $user->save();

            return response()->json([
                'token' => $user->createToken('MyApp')->accessToken,
                'user' => $this->returnUserData($user)
            ], $this->successStatus);
        }

        return response()->json(['message' => 'Invalid email or password!'], 401);

    }

    public function returnUserData($user)
    {
        $userObject = User::find($user->id);
        $userObject->company_id = $user->company()->id;
        $userObject->company = $user->company();
        $userObject->is_admin = $user->hasRoleLike('admin');
        $userObject->is_client = $user->hasRoleLike('client');
        $userObject->is_manager = $user->hasRoleLike('manager');
        $userObject->role = $user->userRole();
        $userObject->can = $user->getPermissions();
        $userObject->is_company_owner = $user->is_company_owner;
        $userObject->is_buzzooka_super_admin = in_array($user->email, config('telescope.allowed_emails'));

        UsersPresence::dispatch($userObject);

        return $userObject;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $user = auth()->user();

        if (request()->has('user_id')) {
            if ($user->id != response()->user_id)
                $user = User::findOrFail(response()->user_id);
        }

        if (Auth::check()) {
            //force stop global timer for the user upon logout
            $timer = $user->lastTimer();

            if ($timer && $timer->action == 'start') {
                $closetimer = Timer::create([
                    'company_id' => $user->company()->id,
                    'timer_name' => $user->first_name . ' Timer',
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

            Auth::user()->oAuthAccessToken()->delete();

            UsersPresence::dispatch($user);

            return response()->json($user->toArray(), 200);
        }
    }
}
