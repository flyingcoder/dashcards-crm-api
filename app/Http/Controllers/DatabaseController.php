<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DatabaseController extends Controller
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function index()
    {
        $results = DB::select('show tables');
        $tables = [];
        $dbname = config('database.connections.mysql.database');
        $props = "Tables_in_".$dbname;
        foreach ($results as $result) {
            $tables[] = $result->{$props};
        }
        $chunks = array_chunk($tables, 10);
        $data = collect([]);
        foreach ($chunks as $chunk) {
            $data->push(DB::select('explain select * from ' . implode(',', $chunk)));
        }
        return $data->flatten();
    }
}
