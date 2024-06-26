<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Report;

class ReportController extends Controller
{
    public function index()
    {
       //(new ReportPolicy())->index();

       $company = auth()->user()->company();

       return $company->companyReports();
    }

    public function newReport()
    {
       $company = auth()->user()->company();

       return $company->createReports();
    }

    public function updateReport($id)
    {
       $report = Report::findOrFail($id);

        if($report->updateReports()) {
            $report->fresh();
            return response()->json($report, 200);
        }
        
        return response()->json(['message' => 'error'], 500);
    }

    public function deleteReport($id)
    {
        $report = Report::findOrFail($id);

        if($report->delete()) 
            return response()->json(['message' => 'success'], 200);

        return response()->json(['message' => 'error'], 500);
    }
}
