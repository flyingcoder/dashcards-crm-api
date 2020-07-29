<?php

namespace App\Http\Controllers;

use App\Dashboard;
use App\Repositories\CalendarEventRepository;
use App\Repositories\FormRepository;

class DashboardController extends Controller
{
    protected $cal_repo;
    protected $formRepo;

    /**
     * DashboardController constructor.
     * @param CalendarEventRepository $cal_repo
     * @param FormRepository $formRepo
     */
    public function __construct(CalendarEventRepository $cal_repo, FormRepository $formRepo)
    {
        $this->cal_repo = $cal_repo;
        $this->formRepo = $formRepo;
    }

    /**
     * @return mixed
     */
    public function addDashitems()
    {
        $company = auth()->user()->company();

        $defaultDash = $company->dashboards()->first();

        request()->validate([
            'dashitem_id' => 'array'
        ]);

        $defaultDash->dashitems()->detach();

        if (request()->has('dashitem_id') && !empty(request()->dashitem_id)) {
            foreach (request()->dashitem_id as $k => $id) {
                $defaultDash->dashitems()->attach($id, ['order' => $k + 1, 'visible' => 1]);
            }
        }

        return Dashboard::findOrFail($defaultDash->id)
            ->dashitems()
            ->orderBy('pivot_order', 'ASC')
            ->get();
    }

    public function counts()
    {

        $company = auth()->user()->company();

        if (auth()->user()->hasRole('admin|manager') || auth()->user()->can('view.all-tasks')) {
            $counts = [
                'projects' => $company->projects()->count(),
                'tasks' => $company->tasks()->where('status', 'open')->count(),
                'calendars' => $this->cal_repo->upcomingEventsForUser(auth()->user(), true),
                'timer' => $company->allTimers()->count(),
                'inbound' => $this->formRepo->getInboundCount($company), //this is about forms questionaires
                'outbound' => $this->formRepo->getOutboundCount($company) //replied questionaires
            ];
        } else {
            $counts = [
                'projects' => auth()->user()->projects()->count(),
                'tasks' => auth()->user()->tasks()->where('status', 'open')->count(),
                'calendars' => $this->cal_repo->upcomingEventsForUser(auth()->user(), true),
                'timer' => auth()->user()->timers()->count(),
                'inbound' => $this->formRepo->getInboundCount($company), //this is about forms questionaires
                'outbound' => $this->formRepo->getOutboundCount($company) //replied questionaires
            ];
        }

        $counts['notification'] = auth()->user()->CountUnreadActivity();
        $counts['chats'] = auth()->user()->CountChats();

        return $counts;
    }

    /**
     * @return mixed
     */
    public function hideAllDashitem()
    {
        $company = auth()->user()->company();

        $defaultDash = $company->dashboards()->first();

        return $defaultDash->dashitems()->detach();
    }

    public function hideDashitem($id)
    {
        $company = auth()->user()->company();

        $defaultDash = $company->dashboards()->first();

        return $defaultDash->dashitems()->detach($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function dashitems($id)
    {
        $dashboard = Dashboard::findOrFail($id);

        return $dashboard->dashitems;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function defaultDashitems()
    {
        $company = auth()->user()->company();

        $defaultDash = $company->dashboards()->first();

        if (!$defaultDash) {
            return collect([]);
        }

        return $defaultDash->dashitems()->orderBy('pivot_order', 'ASC')->get();
    }
}
