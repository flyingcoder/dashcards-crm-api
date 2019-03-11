<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use App\Events\ActivityEvent;

class Reports extends Model
{
    use SoftDeletes, LogsActivity;

    protected $paginate = 10;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title', 'description', 'url', 'company_id'
    ];

    protected static $logName = 'system';

    protected static $logAttributes = [
        'title', 'description', 'url', 'company_id'
    ];

    public function tapActivity(Activity $activity, string $eventName)
    {
        ActivityEvent::dispatch($activity);
    }
    
    public function getDescriptionForEvent(string $eventName): string
    {
        return "A reports has been {$eventName}";
    }

    public function updateReports()
    {   
        request()->validate([
            'title' => 'required',
            'url' => 'required'
        ]);

        return $this->update([
            'title' => request()->title,
            'description' => request()->description,
            'url' => request()->url
        ]);
    }
}
