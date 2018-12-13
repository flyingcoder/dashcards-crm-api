<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Events\UserLogin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user();
            return response()->json([
                'token' => $user->createToken('MyApp')->accessToken, 
                'user' => $user->scopeDefaultColumn()
            ], $this->successStatus); 
        } 
        else{ 
            return response()->json(['message' => 'Invalid email or password!'], 401); 
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
