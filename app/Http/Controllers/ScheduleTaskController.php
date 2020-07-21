<?php

namespace App\Http\Controllers;

use App\ScheduleTask;
use App\Traits\TimezoneTrait;
use App\Traits\ValidationTrait;

class ScheduleTaskController extends Controller
{
    use ValidationTrait, TimezoneTrait;

    protected $allowed_schedule_types = ['email'];

    /**
     * @return mixed
     */
    public function index()
    {
        $schedule_tasks = auth()->user()->company()->scheduleTasks()
            ->withTrashed()
            ->orderBy('deleted_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();
        return [
            'schedule_tasks' => $schedule_tasks,
            'timezones' => $this->splitZonesAndStandards(),
        ];
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function store()
    {
        request()->validate([
            'schedule_type' => 'required|string|in:' . implode(',', $this->allowed_schedule_types),
            'interval_type' => 'required|string',
            'interval_at' => 'sometimes'
        ]);

        $user = auth()->user();
        $props = [];

        if (request()->schedule_type == 'email') {
            request()->validate([
                'from' => 'required',
                'to' => 'required|array',
                'subject' => 'required|string',
                'contents' => 'required|string'
            ]);
            $this->validEmailArray([request()->from]);
            $this->validEmailArray(request()->to);
            $props['name'] = request()->name;
            $props['from'] = request()->from;
            $props['to'] = request()->to;
            $props['subject'] = request()->subject;
            $props['contents'] = request()->contents;
        }

        return $user->scheduleTasks()->create([
            'company_id' => $user->company()->id,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'schedule_type' => request()->schedule_type,
            'interval_type' => request()->interval_type,
            'interval_at' => request()->interval_type != 'every_hour' ? request()->interval_at : null,
            'timezone' => request()->timezone ?? 'UTC',
            'props' => $props
        ]);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function update($id)
    {
        $schedule_task = auth()->user()->scheduleTasks()->withTrashed()->where('id', $id)->first();

        if (!$schedule_task) {
            abort(404, "No schedule task found for id : $id!");
        }
        request()->validate([
            'schedule_type' => 'required|string|in:' . implode(',', $this->allowed_schedule_types),
            'interval_type' => 'required|string',
            'interval_at' => 'sometimes'
        ]);

        $props = [];
        if (request()->schedule_type == 'email') {
            request()->validate([
                'from' => 'required',
                'to' => 'required|array',
                'subject' => 'required|string',
                'contents' => 'required|string'
            ]);
            $this->validEmailArray([request()->from]);
            $this->validEmailArray(request()->to);
            $props['name'] = request()->name;
            $props['from'] = request()->from;
            $props['to'] = request()->to;
            $props['subject'] = request()->subject;
            $props['contents'] = request()->contents;
        }

        $schedule_task->schedule_type = request()->schedule_type;
        $schedule_task->interval_type = request()->interval_type;
        $schedule_task->interval_at = request()->interval_type != 'every_hour' ? request()->interval_at : null;
        $schedule_task->timezone = request()->timezone ?? 'UTC';
        $schedule_task->props = $props;
        $schedule_task->save();

        return $schedule_task->fresh();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleStatus($id)
    {
        $schedule_task = auth()->user()->scheduleTasks()->withTrashed()->where('id', $id)->first();
        if (!$schedule_task) {
            abort(404, "No schedule task found for id : $id!");
        }
        if ($schedule_task->trashed()) {
            $schedule_task->restore();
        } else {
            $schedule_task->next_run_at = null;
            $schedule_task->save();
            $schedule_task->delete();
        }
        return auth()->user()->scheduleTasks()->withTrashed()->where('id', $id)->first();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceDelete($id)
    {
        $schedule_task = auth()->user()->scheduleTasks()->withTrashed()->where('id', $id)->first();
        if (!$schedule_task) {
            abort(404, "No schedule task found for id : $id!");
        }
        $schedule_task->forceDelete();

        return response()->json(['message' => 'Schedule task deleted'], 200);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function histories($id)
    {
        $schedule_task = ScheduleTask::withTrashed()->findOrFail($id);
        return $schedule_task->histories()->latest()->paginate(request()->per_page ?? 10);
    }
}
