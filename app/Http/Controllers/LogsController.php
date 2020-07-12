<?php

namespace App\Http\Controllers;

use App\Activity;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class LogsController extends Controller
{
    protected $types = ['storage', 'telescope'];

    /**
     * @return array
     */
    public function index()
    {
        return [
            'telescope' => [
                'count' => $this->getTelescopeStatistics()
            ]
        ];
    }

    /**
     * @return int
     */
    public function getTelescopeStatistics()
    {
        return DB::select('explain select * from telescope_entries')[0]->rows ?? 0;
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getActivityLogs()
    {
        return Activity::with('causer_user')->latest()->paginate(request()->per_page ?? 50);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function clear()
    {
        request()->validate(['type' => 'required|in:' . implode(',', $this->types)]);

        if (request()->type == 'telescope') {
            Artisan::call('telescope:prune', ['--hours' => 12]);
        }

        return response()->json($this->index(), 200);
    }
}
