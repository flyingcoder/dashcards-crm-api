<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminApiController extends Controller
{
    public function showLogin(){
    	return view('auth.api-login');
    }

	public function adminLogin(Request $request){
		$request->validate([
			'email' => 'required|email',
			'password' => 'required'
		]);
		$allowed_emails = config('telescope.allowed_emails');
		if (!in_array($request->email, $allowed_emails)) {
			abort(404, 'Invalid credentials');
		}
		if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
	        return redirect()->route('telescope');
	    } else {
	        return redirect()->back();
	    }
	}

}
