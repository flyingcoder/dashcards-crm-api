 <?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Dashboard;

use App\Dashitem;

use Illuminate\Database\QueryException;

class DashboardController extends Controller
{
    public function addDashitems()
    {
        $company = auth()->user()->company();

        $defaultDash = $company->dashboards()->first();

        request()->validate([
            'dashitem_id' => 'required|array'
        ]);

        $defaultDash->dashitems()->detach();

        foreach (request()->dashitem_id as $k => $id) {
            $defaultDash->dashitems()->attach($id, ['order' => $k+1, 'visible' => 1]);
        }

        return Dashboard::findOrFail($defaultDash->id)->dashitems;
    }

    public function counts() {

    	$company = auth()->user()->company();

    	if(auth()->user()->hasRole('admin|manager') && auth()->user()->can('view.all-tasks')) {
    		$counts = [
	        	'projects' => $company->projects()->count(),
	        	'tasks' => $company->tasks()->where('status', 'open')->count(),
	        	'calendars' => 0, //to be added soon
	        	'timer' => $company->tasks()->where('status', 'open')->count(),
	        	'inbound' => 0, //this is about forms questionaires
	        	'outbound' => 0 //replied questionaires
	        ];
    	} else {
    		$counts = [
    			'projects' => auth()->user()->projects()->count(),
	        	'tasks' => auth()->user()->tasks()->where('status', 'open')->count(),
	        	'calendars' => 0, //to be added soon
	        	'timer' => auth()->user()->tasks()->where('status', 'open')->count(),
	        	'inbound' => 0, //this is about forms questionaires
	        	'outbound' => 0 //replied questionaires
    		];
    	}
    }

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

    public function dashitems($id)
    {
    	$dashboard = Dashboard::findOrFail($id);

    	return $dashboard->dashitems;
    }

    public function defaultDashitems()
    {
    	$company = auth()->user()->company();

    	$defaultDash = $company->dashboards()->first();
        dd($defaultDash->dashitems);
    	return $defaultDash->dashitems;
    }
}
