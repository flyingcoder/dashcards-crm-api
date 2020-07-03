<?php

namespace App\Http\Controllers;

use App\Activity;
use Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogsController extends Controller
{
	protected $types = ['storage', 'telescope'];

    public function index()
    {
    	return [
            'telescope' => [
                'count' => $this->getTelescopeStatistics()
            ]
        ];
    }

    public function getTelescopeStatistics()
    {
        $entries = DB::select('explain select * from telescope_entries')[0]->rows ?? 0;
        return $entries;
    }

    public function getActivityLogs()
    {
        return Activity::with('causer_user')->latest()->paginate(request()->per_page ?? 50);
    }

    public function clear()
    {
    	request()->validate(['type' => 'required|in:'.implode(',', $this->types)]);

    	if (request()->type == 'telescope') {
    		Artisan::call('telescope:prune', [ '--hours' => 12 ]);
		}	

		return response()->json($this->index(), 200);
    }
}
