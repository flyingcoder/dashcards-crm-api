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

       return $report->updateReports();
    }

    public function delete($id)
    {
        $report = Report::findOrFail($id);

        return $report->destroy();
    }
}
