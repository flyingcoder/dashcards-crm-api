<?php

namespace App\Http\Controllers;

use App\Company;
use App\Events\NewClientCreated;
use App\Events\NewUserCreated;
use App\Mail\UserCredentials;
use App\Repositories\InvoiceRepository;
use App\Repositories\MembersRepository;
use App\Traits\HasConfigTrait;
use App\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

/**
 * Class ClientController
 * @package App\Http\Controllers
 */
class ClientController extends Controller
{
    use HasConfigTrait;
    /**
     * @var int
     */
    private $paginate = 10;

    /**
     * @var InvoiceRepository
     */
    protected $invoiceRepo;
    /**
     * @var MembersRepository
     */
    protected $memberRepo;

    /**
     * ClientController constructor.
     * @param InvoiceRepository $invoiceRepo
     * @param MembersRepository $memberRepo
     */
    public function __construct(InvoiceRepository $invoiceRepo, MembersRepository $memberRepo)
    {
        $this->invoiceRepo = $invoiceRepo;
        $this->memberRepo = $memberRepo;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function perCompany()
    {
        $company = Auth::user()->company();
        $clientTeam = $company->clientTeam();
        return $clientTeam->members()->with('clientCompanies')->get();
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $company = Auth::user()->company();
        $this->memberRepo->setCompany($company);
        if (request()->has('all') && request()->all) {
            $clients = $this->memberRepo->getUsersByType('clients', [], false);
            foreach ($clients as $key => $client) {
                $clients[$key]->company = $client->clientCompanies()->first() ?? null;
            }
            return $clients;
        }

        $clients = $this->memberRepo->getUsersByType('clients', [], true);

        $items = $clients->getCollection();
        $data = collect([]);
        foreach ($items as $key => $client) {
            $client->is_client = true;
            $client->projects = $client->projects()->count();
            $companies = $client->clientCompanies;
            $data->push(array_merge($client->toArray(), ['company' => $companies[0], 'companies' => $companies]));
        }
        $clients->setCollection($data);

        return $clients;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function client($id)
    {
        $client = User::findOrFail($id);
        $client->getAllMeta();
        $client->company = $client->mainCompany;
        $client->companies = $client->clientCompanies;
        $client->no_invoices = $this->invoiceRepo->countInvoices($client, 'all');
        $client->total_amount_paid = $this->invoiceRepo->totalInvoices($client, 'billed_to', 'paid');
        $client->total_amount_unpaid = $this->invoiceRepo->totalInvoices($client, 'billed_to', 'pending');
        return $client;
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addStaffs($id)
    {
        abort(404);
        try {
            DB::beginTransaction();
            $validation = [
                'last_name' => 'required|string',
                'first_name' => 'required|string',
                'email' => 'required|email|unique:users',
                'telephone' => 'required',
                'job_title' => 'string',
            ];
            $hasPassword = false;
            if (request()->has('admin_set_password') && request()->admin_set_password) {
                $validation['password'] = 'required|string|min:6|confirmed';
                $hasPassword = true;
            }

            request()->validate($validation);
            $username = explode('@', request()->email)[0];
            $image_url = random_avatar();

            $member = User::create([
                'username' => $username . rand(0, 20),
                'last_name' => request()->last_name,
                'first_name' => request()->first_name,
                'email' => request()->email,
                'telephone' => request()->telephone,
                'job_title' => request()->job_title,
                'password' => $hasPassword ? bcrypt(request()->password) : bcrypt(str_random(12)),
                'image_url' => $image_url,
                'created_by' => auth()->user()->id,
                'props' => [
                    'address' => request()->address ?? 'Unknown',
                    'rate' => request()->rate ?? ''
                ]
            ]);

            $company = auth()->user()->company();
            $role = request()->group_name;
            $team = $company->defaultTeam();

            if (auth()->user()->hasRole('client')) {
                $team = $company->clientStaffTeam();
                $role = 'client';
            }

            $team->members()->attach($member);
            $member->assignRole($role);
            $member->group_name = $role;
            $member->tasks = 0;
            $member->projects = 0;

            DB::commit();

            Mail::to($member->email)->send(new UserCredentials($member, request()->password ?? null));
            broadcast(new NewUserCreated($member));

            return $member;
        } catch (Exception $e) {
            DB::rollback();
            $error_code = $e->getCode();
            switch ($error_code) {
                case 1062:
                    return response()->json(['message' => 'The company email you have entered is already registered.'], 500);
                    break;
                case 1048:
                    return response()->json(['message' => 'Some fields are missing.'], 500);
                default:
                    return response()->json(['message' => $e->getMessage()], 500);
                    break;
            }
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function tasks($id)
    {
        $client = User::findOrFail($id);

        return $client->tasks()->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function staffs($id)
    {
        $client = User::findOrFail($id);

        return $client->clientStaffs();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function invoices($id)
    {
        $client = User::findOrFail($id);
        return $this->invoiceRepo->getClientInvoices($client);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function details($id)
    {
        return view('pages.client-details');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function save()
    {
        return view('pages.clients-new', [
            'action' => 'add'
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        if (request()->has('client_id')) {
            return $this->saveAsExistingClient();
        }
        return $this->saveAsNewClient();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveAsExistingClient()
    {
        request()->validate([
            'client_id' => 'required|exists:users,id',
            'company_name' => 'required|string|min:1'
        ]);
        try {
            DB::beginTransaction();
            $client = User::findOrFail(request()->client_id);

            $client_company = Company::create([
                'name' => request()->company_name,
                'is_private' => 1,
                'address' => request()->location ?? $client->location,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'others' => [
                    'contact_name' => request()->contact_name ?? $client->fullname
                ]
            ]);
            $client->companies()->attach($client_company->id, ['type' => 'client']);
            DB::commit();
            $client->is_client = true;
            $client->companies = $client->clientCompanies;
            $client->projects =  $client->projects()->count();

            return  $client;
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveAsNewClient()
    {
        request()->validate([
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'status' => 'required',
            'company_name' => 'required',
        ]);

        $username = explode('@', request()->email)[0];
        try {
            DB::beginTransaction();
            $company = Auth::user()->company();
            $client_company = Company::create([
                'name' => request()->company_name,
                'is_private' => 1,
                'address' => request()->location ?? null,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'others' => [
                    'contact_name' => request()->contact_name ?? null
                ]
            ]);

            $client = User::create([
                'username' => $username,
                'last_name' => request()->last_name,
                'first_name' => request()->first_name,
                'email' => request()->email,
                'telephone' => request()->telephone ?? null,
                'job_title' => 'Client',
                'password' => bcrypt(request()->password),
                'image_url' => random_avatar('neutral'),
                'created_by' => Auth::user()->id,
                'props' => [
                    'company_id' => $company->id,
                    'status' => request()->status ?? 'Active'
                ]
            ]);

            $client->companies()->attach($company->id, ['type' => 'main']);
            $client->companies()->attach($client_company->id, ['type' => 'client']);

            $team = $company->teams()->where('slug', 'client-' . $company->id)->first();
            if ($team) {
                $team->members()->attach($client);
            }

            $client->assignRole('client');

            DB::commit();

            $client->companies = $client->clientCompanies;
            $client->is_client = true;
            $client->projects = 0;

            $config = $this->getConfigByKey('email_events', false);
            if ($config && $config->new_client)
               event(new NewClientCreated($client));

            return $client;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id)
    {
        $client = User::findOrFail($id);
        request()->validate([
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'email' => ['required', 'email', Rule::unique('users')->ignore($client->id)],
            'telephone' => 'sometimes',
            'status' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $client->first_name = request()->first_name;
            $client->last_name = request()->last_name;
            $client->email = request()->email;
            $client->telephone = request()->telephone;
            if (request()->has('password')) {
                $client->password = bcrypt(request()->password);
            }
            $props = $client->props;
            $props['status'] = request()->status ?? 'Active';
            $props['updated_by'] = Auth::user()->id;
            $client->props = $props;
            $client->save();

            $companies = $client->clientCompanies;
            $company = $companies[0] ?? null;

            DB::commit();
            $client->is_client = true;
            $client->company = $company;
            $client->companies = $companies;
            $client->projects = $client->projects()->count();

            return $client;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $client = User::findOrFail($id);

        if ($client->projects()->count() != 0)
            abort(401, "This client has open project please delete the project first.");

        if ($client->delete()) {
            return response()->json(['message' => 'Client successfully deleted'], 200);
        } else {
            return response()->json(['message' => "Client can't be deleted"], 500);
        }

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkDelete()
    {
        request()->validate([
            'ids' => 'required|array'
        ]);

        try {
            DB::beginTransaction();
            $clients = User::whereIn('id', request()->ids)->with('projects')->get();

            if ($clients) {
                foreach ($clients as $key => $client) {
                    if ($client->projects->count() != 0) {
                        throw new \Exception(" Client {$client->fullname} has an open project, please delete the project first.", 1);
                    }
                    $client->delete();
                }
            }

            DB::commit();
            return response()->json(['message' => $clients->count() . ' client(s) was successfully deleted'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => "Some client has open project please delete the project first"], 500);
        }
    }

}
