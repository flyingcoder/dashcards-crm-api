<?php

namespace App\Http\Controllers;

use App\Report;
use App\Traits\HasUrlTrait;

class ReportController extends Controller
{
    use HasUrlTrait;

    /**
     * @return mixed
     */
    public function index()
    {
        //(new ReportPolicy())->index();

        $company = auth()->user()->company();

        return $company->companyReports();
    }

    /**
     * @return mixed
     */
    public function newReport()
    {
        request()->validate([
            'title' => 'required',
            'url' => 'required'
        ]);

        $company = auth()->user()->company();

        return $company->reports()->create([
            'title' => request()->title,
            'description' => request()->description ?? null,
            'url' => request()->url,
            'props' => $this->getPreviewArray(request()->url)
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateReport($id)
    {
        $report = Report::findOrFail($id);

        if ($report->updateReports()) {
            $report->fresh();
            return response()->json($report, 200);
        }

        return response()->json(['message' => 'error'], 500);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteReport($id)
    {
        $report = Report::findOrFail($id);

        if ($report->delete())
            return response()->json(['message' => 'success'], 200);

        return response()->json(['message' => 'error'], 500);
    }

}
