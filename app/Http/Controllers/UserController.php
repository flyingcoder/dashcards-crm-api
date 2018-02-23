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
