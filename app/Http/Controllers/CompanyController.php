<?php

namespace App\Http\Controllers;

use App\Company;
use App\Events\CompanyEvent;
use App\Repositories\MembersRepository;
use App\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class CompanyController
 * @package App\Http\Controllers
 */
class CompanyController extends Controller
{
    /**
     * @var MembersRepository
     */
    protected $repo;

    /**
     * CompanyController constructor.
     * @param MembersRepository $repo
     */
    public function __construct(MembersRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @var array
     */
    protected  $types = [
            'manager',
            'client',
            'member',
            'agent'
        ];

    /**
     * @return mixed
     */
    public function subscribers()
    {
        $users = User::admins()->withTrashed()->paginate(request()->per_page);
        $items = $users->getCollection();

        $data = collect([]);
        foreach ($items as $key => $user) {
            $data->push(array_merge($user->toArray(), ['company' =>  $user->company() ]));   
        }
        $users->setCollection($data);

        return $users;
    }

    /**
     * @return mixed
     */
    public function members()
    {
        if (request()->has('type') && in_array(request()->type, $this->types)) {
            $type = trim(request()->type);
            return auth()->user()->company()
                ->members()
                ->select('users.*')
                ->with('roles')
                ->whereHas('roles', function (Builder $query) use ($type) {
                        $query->where('slug', 'like', "%{$type}%");
                })->get();
            
        }
    	return auth()->user()->company()->allCompanyMembers();
    }

    /**
     * @return mixed
     */
    public function clients()
    {
        $company = auth()->user()->company();
        $clients = $company->clients();
        return $clients->get();
    }

    /**
     * @return User[]|\Illuminate\Contracts\Pagination\LengthAwarePaginator|Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function teams()
    {
        if(auth()->user()->hasRole('client'))
            return auth()->user()->clientStaffs();

    	if(request()->has('all') && request()->all)
            return auth()->user()->company()->allTeamMembers();
    	if (request()->has('basics')) {
            return $this->repo->companyUserList(auth()->user()->company());
        }
        return auth()->user()->company()->paginatedCompanyMembers();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function member($id)
    {
    	$user = User::findOrFail($id);

        $user->getAllMeta();

        $user['week_hours'] = $user->totalTimeThisWeek();
        
        $roles = $user->roles()->first();
        if(!is_null($roles))
            $user['group_name'] = $roles->id;

        $user->tasks;

        return $user;
    }

    /**
     * @return mixed
     */
    public function invoices()
    {
        return auth()->user()->company()->invoices()->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function uploadLogo($id)
    {
        $file = request()->file('file');
        $company = Company::findOrFail($id);
        $media = $company->addMedia($file)
                        ->usingFileName('company-'.$company->id.".png")
                        ->toMediaCollection('company-logo');

        $company->company_logo = url($media->getUrl());
        $company->save();

        broadcast(new CompanyEvent($company->id, array_merge(['type' => 'configs'], $company->toArray())));

        return $company;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function info($id)
    {
        return Company::findOrFail($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function updateInfo($id)
    {
        request()->validate(['name' => 'required|min:5']);

        $company = Company::findOrFail($id);
        $company->name = request()->name;
        $company->short_description = request()->short_description ?? null;
        $company->long_description = request()->long_description ?? null;
        $company->domain = request()->domain ?? null;
        $company->address = request()->address ?? null;
        $company->contact = request()->contact ?? null;
        $company->email = request()->email ?? null;
        $company->save();

        return $company;
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSettings($id)
    {
        request()->validate([
                'title' => 'required|string',
                'lang' => 'required|string',
                'theme' => 'required|string',
                'date_format' => 'required|string',
                'timeline_display_limits' => 'required|digits_between:1,100',
                'general_page_limits' => 'required|digits_between:1,100',
                'messages_page_limits' => 'required|digits_between:1,100',
                'currency' => 'required|array',
                'info_tips' => 'required|in:'.implode(',', ['Yes', 'No']),
                'client_registration' => 'required|in:'.implode(',', ['Yes', 'No']),
                'notif_duration' => 'required|digits_between:1,86400',
                // 'license_key' => '',
                // 'long_logo'
                // 'square_logo'
            ]);

        $company = Company::findOrFail($id);
        $settings = $company->others;

        $settings['title'] = request()->title;
        $settings['lang'] = request()->lang;
        $settings['theme'] = request()->theme;
        $settings['date_format'] = request()->date_format;
        $settings['timeline_display_limits'] = request()->timeline_display_limits;
        $settings['general_page_limits'] = request()->general_page_limits;
        $settings['messages_page_limits'] = request()->messages_page_limits;
        $settings['currency'] = request()->currency;
        $settings['info_tips'] = request()->info_tips;
        $settings['client_registration'] = request()->client_registration;
        $settings['notif_duration'] = request()->notif_duration;
        $settings['license_key'] = request()->license_key;

        $company->others = $settings;
        $company->save();

        return response()->json($settings, 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function settings($id)
    {
        $company = Company::findOrFail($id);
        $settings = (array) $company->others;
        $defaultSettings = $this->defaultSettings();
        if (empty($settings)) {
            $settings =  $defaultSettings;
        }
        foreach ($settings as $key => $setting) {
            if (empty($settings[$key]) || $settings[$key] === 0 ) {
                $settings[$key] = $defaultSettings[$key];
            }
        }
        return response()->json($settings, 200);
    }

    /**
     * @return array
     */
    public function defaultSettings()
    {
        return [
            'title' => 'Buzzooka Dashboard',
            'lang' => 'english',
            'theme' => 'default',
            'date_format' => 'Y-M-D',
            'timeline_display_limits' => 15,
            'general_page_limits' => 12,
            'messages_page_limits' => 12,
            'currency' => ["text" => "US Dollar", "symbol" => "$", "currency_code" => "USD"],
            'info_tips' => 'No',
            'client_registration' => 'No',
            'notif_duration' => 1800,
            'license_key' => null,
        ];
    }

    /**
     * @return array
     */
    public function subscribersStatistics()
    {
        return [
            'inactive_companies' => Company::onlyTrashed()->where('is_private', 0)->count(),
            'active_companies' => Company::where('is_private', 0)->count(),
            'active_users' => User::count(),
            'inactive_users' => User::onlyTrashed()->count(),
        ];
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function companies()
    {
        $companies = Company::withTrashed()->where('is_private', 0)->latest()->paginate(40);

        $companiesItems = $companies->getCollection();
        $data = collect([]);

        foreach ($companiesItems as $key => $company) {
            $members = $company->members()->take(500)->get(); 
            $owner = $members->filter->where('id' , $company->companyOwner->id)->first();

            $data->push(array_merge($company->toArray(), ['members' => $members, 'owner' => $owner ]));   
        }

        $companies->setCollection($data);
        return $companies;
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function companyStatus($id)
    {
        $company = Company::withTrashed()->where('id', $id)->firstOrFail();
        if ($company->trashed()) {
            $company->restore();
        } else {
            $company->delete();
        }
            
        $members = $company->members()->take(500)->get(); 
        $owner = $members->filter->where('id' , $company->companyOwner->id)->first();

        return response()->json(array_merge($company->toArray(), ['members' => $members, 'owner' => $owner ]), 200);
    }
}