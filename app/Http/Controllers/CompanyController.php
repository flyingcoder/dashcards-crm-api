<?php

namespace App\Http\Controllers;

use App\Company;
use App\Repositories\MembersRepository;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class CompanyController extends Controller
{
    protected $repo;

    public function __construct(MembersRepository $repo)
    {
        $this->repo = $repo;
    }

    protected  $types = [
            'manager',
            'client',
            'member',
            'agent'
        ];

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

    public function invoices()
    {
        return auth()->user()->company()->invoices()->get();
    }

    public function uploadLogo($id)
    {
        $file = request()->file('file');
        $company = Company::findOrFail($id);
        $media = $company->addMedia($file)
                        ->usingFileName('company-'.$company->id.".png")
                        ->toMediaCollection('company-logo');

        $company->company_logo = url($media->getUrl());
        $company->save();

        return $company;
    }

    public function info($id)
    {
        $company = Company::findOrFail($id);
        return $company;
    }

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

    public function settings($id)
    {
        $company = Company::findOrFail($id);
        $settings = $company->others;

        return response()->json($settings, 200);
    }
}
