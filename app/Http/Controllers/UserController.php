<?php

namespace App\Http\Controllers;

use App\Mail\UserCredentials;
use App\Policies\UserPolicy;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Storage;

class UserController extends Controller
{

    public function user()
    {
        $user = request()->user();
        $user->is_admin = $user->hasRole('admin');
        $user->can = $user->getPermissions();
        return $user;
    }

    public function notifications()
    {
        return auth()->user()->notifications;
    }

    public function editProfilePicture($id)
    {
        //(new UserPolicy())->update($model);

        $model = User::findOrFail($id);

        $media = $model->addMedia(request()->file('file'))
                        ->usingFileName('profile-'.$model->id.".png")
                        ->toMediaCollection('avatars');
 
        $model->image_url = url($media->getUrl('thumb'));

        $model->save();

        return $model;
    }

    public function store(Request $request)
    {
        (new UserPolicy())->create();
        $validation = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'group' => 'integer|exists:role,id',
            'job_title' => 'string',
            'telephone' => 'string'
        ];

        $hasPassword =  false;
        if ($request->has('admin_set_password') && $request->admin_set_password) {
            $validation['password'] = 'required|string|min:6|confirmed';
            $hasPassword =  true;
        }

    	$request->validate($validation);
        
        $additionalInfo = [
            'image_url' => random_avatar(),
            'password' => $hasPassword ? bcrypt($request->password) : bcrypt(str_random(12))
        ];

        $user = User::create(request()->all() + $additionalInfo);
        $user->setMeta('address', request()->address ?? '');
        $user->setMeta('rate', request()->rate ?? '');

        \Mail::to($user->email)->send(new UserCredentials($user, $request->get('password',null)));

        return $user->fresh();

    }

    
    public function updatePassword(Request $request)
    {
        $validation = [
            'user_id' => 'required|numeric|exists:users,id',
            'password' => 'required|string|min:6|confirmed',
            'required_current_password' => 'required|boolean'
        ];
        if ($request->required_current_password) {
            $validation['current_password'] = 'required|string';
        }
        $request->validate($validation);
        $user = User::findOrFail($request->user_id);

        if ($request->required_current_password && !Hash::check($request->current_password, $user->password)) {
            abort(500, 'Current password does not match with user password');
        }
        
        $user->password = bcrypt($request->password);
        $user->save();

        return $user->fresh();
    }

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

    public function userTasks($user_id)
    {
        $user = User::findOrFail($user_id);

        $tasks = $user->tasks;

        $tasks->map(function ($task, $key) use ($user) {
            $task['assignee_url'] = $user->image_url;
        });

        return $tasks;
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

    public function userTimers($user_id)
    {
        $user = User::findOrFail($user_id);

        return $user->paginatedUserTimers();
    }
}
