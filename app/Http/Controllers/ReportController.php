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
        $props = ['creator' => request()->user()->id];
        return $company->reports()->create([
            'title' => request()->title,
            'description' => request()->description ?? null,
            'url' => request()->url,
            'props' => array_merge($this->getPreviewArray(request()->url), $props)
        ]);
    }

    /**
     * @return mixed
     */
    public function newReportViaTemplate()
    {
        request()->validate([
            'title' => 'required',
            'structures' => 'required|array',
            'template' => 'required|exists:templates,id'
        ]);

        $company = auth()->user()->company();

        return $company->reports()->create([
            'title' => request()->title,
            'description' => request()->description ?? null,
            'url' => 'template',
            'props' => [
                'creator' => request()->user()->id,
                'template' => request()->structures,
                'template_id' => request()->template
            ],
            'project_id' => request()->project_id ?? null
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
     * @return mixed
     */
    public function updateReportViaTemplate($id)
    {
        request()->validate([
            'title' => 'required|min:1',
            'structures' => 'required|array',
            'template' => 'required|exists:templates,id'
        ]);

        $report = Report::findOrFail($id);
        $report->title = request()->title;
        $props = $report->props;
        $props['template_id'] = request()->template;
        $props['template'] = request()->structures;
        $report->props = $props;
        $report->save();

        return $report->fresh();
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
