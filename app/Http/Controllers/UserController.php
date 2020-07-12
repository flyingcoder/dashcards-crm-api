<?php

namespace App\Http\Controllers;

use App\Mail\UserCredentials;
use App\Policies\UserPolicy;
use App\Repositories\TimerRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $timerRepo;

    /**
     * UserController constructor.
     * @param TimerRepository $timerRepo
     */
    public function __construct(TimerRepository $timerRepo)
    {
        $this->timerRepo = $timerRepo;
    }

    /**
     * @return mixed
     */
    public function user()
    {
        $userObject = User::findOrFail(request()->user()->id);

        $userObject->company = $userObject->company();
        $userObject->company_id = $userObject->company->id;
        $userObject->is_admin = $userObject->hasRoleLike('admin');
        $userObject->is_client = $userObject->hasRoleLike('client');
        $userObject->is_manager = $userObject->hasRoleLike('manager');
        $userObject->is_company_owner = $userObject->getIsCompanyOwnerAttribute();
        $userObject->role = $userObject->userRole();
        $userObject->can = $userObject->getPermissions();
        $userObject->is_buzzooka_super_admin = in_array($userObject->email, config('telescope.allowed_emails'));

        return $userObject;
    }

    /**
     * @return mixed
     */
    public function notifications()
    {
        return auth()->user()->notifications;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function editProfilePicture($id)
    {
        //(new UserPolicy())->update($model);

        $model = User::findOrFail($id);

        $media = $model->addMedia(request()->file('file'))
            ->usingFileName('profile-' . $model->id . ".png")
            ->toMediaCollection('avatars');

        $model->image_url = url($media->getUrl('thumb'));

        $model->save();

        return $model;
    }

    /**
     * @param Request $request
     * @return mixed
     */
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

        $hasPassword = false;
        if ($request->has('admin_set_password') && $request->admin_set_password) {
            $validation['password'] = 'required|string|min:6|confirmed';
            $hasPassword = true;
        }

        $request->validate($validation);

        $additionalInfo = [
            'image_url' => random_avatar(),
            'password' => $hasPassword ? bcrypt($request->password) : bcrypt(str_random(12))
        ];

        $user = User::create(request()->all() + $additionalInfo);
        $user->setMeta('address', request()->address ?? '');
        $user->setMeta('rate', request()->rate ?? '');

        \Mail::to($user->email)->send(new UserCredentials($user, $request->get('password', null)));

        return $user->fresh();

    }


    /**
     * @param Request $request
     * @return mixed
     */
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

    /**
     * @param $key
     * @return mixed
     */
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

    /**
     * @return mixed
     */
    public function addBankTransferDetails()
    {
        $company = auth()->user()->company();

        $company->setMeta('bank-transfer-details', [
            'gateway_active' => request()->gateway_active,
            'payment_instructions' => request()->payment_instructions
        ]);

        return $company;
    }

    /**
     * @return mixed
     */
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

    /**
     * @return mixed
     */
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

    /**
     * @return mixed
     */
    public function projects()
    {
        return auth()->user()->userPaginatedProject(request());
    }

    /**
     * @return mixed
     */
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

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function show(Request $request)
    {
        (new UserPolicy())->show(User::findOrFail($request->id));

        if ($request->ajax())
            return response()->json(User::findOrFail($request->id));

        return view('user.profile', ['user' => User::findOrFail($request->id)]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $per_page = isset($request->per_page) ? $request->per_page : 15;
        if ($request->ajax())
            return response()->json(User::all()->paginate($per_page));
        return view('user.index', ['user' => User::all()->paginate($per_page)]);
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function userTimers($user_id)
    {
        $user = User::findOrFail($user_id);

        return $user->paginatedUserTimers();
    }

    /**
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function userGlobalTimers($user_id)
    {
        $user = User::findOrFail($user_id);
        $last_timer = $user->timers()->latest()->first();

        $today = 'today';
        if ($last_timer && $last_timer->status == 'open') {
            $today = $last_timer->created_at->format('Y-m-d');
        }
        return response()->json([
            'today' => $this->timerRepo->getTimerForUser($user, $today),
            'monthly' => $this->timerRepo->getTimerForUserFromTo($user, now()->startOfMonth()->format('Y-m-d'), now()->endOfMonth()->format('Y-m-d')),
            'is_started' => $last_timer && $last_timer->status === 'open'
        ], 200);
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function userTaskTimers($user_id)
    {
        $user = User::findOrFail($user_id);
        $tasks = $user->tasks()
            ->where('tasks.status', '<>', 'completed')
            ->select('tasks.*')
            ->with('assigned')
            ->orderBy('tasks.status', 'DESC')
            ->orderBy('tasks.id', 'ASC')
            ->paginate(10);

        $tasksItems = $tasks->getCollection();
        $data = collect([]);

        foreach ($tasksItems as $key => $task) {
            $timer = $this->timerRepo->getTimerForTask($task);
            $service = $task->milestone->project->service->name ?? '';

            $data->push(array_merge($task->toArray(), ['timer' => $timer, 'service' => $service]));
        }

        $tasks->setCollection($data);

        return $tasks;

    }
}
