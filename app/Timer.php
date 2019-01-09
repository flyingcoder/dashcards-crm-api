<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'timer_name', 
        'description', 
        'subject_id', 
        'subject_type', 
        'causer_id', 
        'causer_type', 
        'properties',
        'action',
        'status'
    ];

    protected $dates = ['deleted_at'];

    public function trigger($action)
    {
        request()->validate([
            'id' => 'required_with:type|integer'
        ]);
        
        $type = 'App\\Company';

        $last_timer = auth()->user()->lastTimer();

        $subject_id = auth()->user()->company()->id;

        $description = 'A general timer';

        if(request()->has('type')) {

            $type = 'App\\'.ucfirst(request()->type);

            $task = Task::findOrFail(request()->id);

            $subject_id = request()->id;

            $last_timer = $task->lastTimer();

            $description = 'A task timer';

        }

        if($last_timer != null)
            $this->checkAction($last_timer, $action);
        else if($last_timer == null && $action != 'start')
            return response('Action is not allowed', 405);
        

        if(request()->has('description'))
            $description = request()->description;

        $timer = Timer::create([
            'company_id' => auth()->user()->company()->id,
            'timer_name' => auth()->user()->first_name.' Timer',
            'description' => $description,
            'subject_id' => $subject_id,
            'subject_type' => $type,
            'causer_id' => auth()->user()->id,
            'causer_type' => 'App\\User',
            'action' => $action,
            'status' => $this->getStatus($action),
        ]);

        if($action == 'pause')
            $this->ifPause($timer, $last_timer);

        if($action == 'stop')
            $this->ifStop($timer, $last_timer);

        return $timer;
    }

    public function ifStop($timer, $last_timer)
    {
        if($timer->subject_type == 'App\Company')
            $model = User::findOrFail($timer->causer_id);
        else
            $model = Task::findOrFail($timer->subject_id);
        
        $open_timer = $model->timers()
                            ->where('status', 'open');

        $start = Carbon::parse($last_timer->created_at);

        $end = Carbon::now();

        $total_sec = $end->diffInSeconds($start);

        $paused_timer = $open_timer->where('action', 'pause')->get();

        foreach ($paused_timer as $value) {
            $properties = json_decode($value->properties);
            $total_sec = $total_sec + intval($properties->total_seconds);
        }

        $args = collect([
            'total_time' => gmdate("H:i:s", $total_sec),
            'total_seconds' => $total_sec
        ]);

        $timer->update(['properties' => $args->toJson()]);

        //dd($open_timer);
        $model->timers()
              ->where('status', 'open')
              ->update(['status' => 'close']);
    }

    public function ifPause($timer, $last_timer)
    {
        $start = Carbon::parse($last_timer->created_at);

        $end = Carbon::now();

        $total_sec = $end->diffInSeconds($start);

        $args = collect([
            'total_time' => gmdate("H:i:s", $total_sec),
            'total_seconds' => $total_sec
        ]);

        $timer->update(['properties' => $args->toJson()]);
    }

    public function subject()
    {
    	$this->morphTo();
    }

    public function causer()
    {
    	$this->morphTo();
    }

    public function getStatus($action)
    {
        $status = 'close';

        if($action != 'stop')
            $status = 'open';

        return $status;
    }

    public function checkAction($last_timer, $action)
    {
        if($last_timer->action == $action)
            abort(405, 'Action is not allowed');

        if($last_timer->action == 'pause' && $action != 'back')
            abort(405, 'Action is not allowed');

        if($last_timer->action == 'stop' && $action != 'start')
            abort(405, 'Action is not allowed');

        if($last_timer->action == 'back' && $action == 'start')
            abort(405, 'Action is not allowed');
    }

    public static function boot()
    {
        Timer::creating(function ($timer) {
            
        });
    }
}
