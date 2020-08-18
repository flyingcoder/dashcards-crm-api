<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Campaign;
use App\Project;

/**
 * Class ActivityController
 * @package App\Http\Controllers
 */
class ActivityController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        $company = auth()->user()->company();
        $per_page = request()->has('per_page') ? (int) request()->per_page : 5;

        if (request()->has('page') && request()->page > 0) {
            $timelines = $company->timeline()
                ->where('log_name', 'files')
                ->latest()
                ->paginate($per_page);

            $items = $timelines->getCollection();

            $data = collect([]);
            foreach ($items as $key => $activity) {
                $data->push(array_merge($activity->toArray(), ['attachments' => $activity->attachments()]));
            }

            $timelines->setCollection($data);

            return $timelines;
        }

        return $company->allTimeline();
    }

    /**
     * @return mixed
     */
    public function log()
    {
        $company = auth()->user()->company();

        return $company->activityLog();
    }

    /**
     * @param $project_id
     * @return mixed
     */
    public function project($project_id)
    {
        $project = Project::findOrFail($project_id);
        $per_page = request()->has('per_page') ? (int) request()->per_page : 5;

        if (request()->has('page') && request()->page > 0) {
            $timelines = $project->activity()
                ->where('log_name', 'files')
                ->latest()
                ->paginate($per_page);

            $items = $timelines->getCollection();

            $data = collect([]);
            foreach ($items as $key => $activity) {
                $data->push(array_merge($activity->toArray(), ['attachments' => $activity->attachments()]));
            }

            $timelines->setCollection($data);

            return $timelines;
        }

        return $project->activity;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function service($id)
    {
        $service = Campaign::findOrFail($id);
        $per_page = request()->has('per_page') ? (int) request()->per_page : 5;

        if (request()->has('page') && request()->page > 0) {
            $timelines = $service->activity()
                ->where('log_name', 'files')
                ->latest()
                ->paginate($per_page);

            $items = $timelines->getCollection();

            $data = collect([]);
            foreach ($items as $key => $activity) {
                $data->push(array_merge($activity->toArray(), ['attachments' => $activity->attachments()]));
            }

            $timelines->setCollection($data);

            return $timelines;
        }

        return $service->activity;
    }

    /**
     * @param $id
     */
    public function markRead($id)
    {
        $company = Activity::findOrFail($id);
    }

    /**
     * @return mixed
     */
    public function unread()
    {
        $company = auth()->user()->company();

        return $company->activityLogUnRead();
    }
}
