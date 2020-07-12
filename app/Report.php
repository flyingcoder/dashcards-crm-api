<?php

namespace App;

use App\Events\ActivityEvent;
use App\Traits\HasUrlTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Report extends Model
{
    use SoftDeletes, LogsActivity, HasUrlTrait;

    protected $paginate = 10;

    protected $dates = ['deleted_at'];

    protected $casts = ['props' => 'array'];

    protected $fillable = [
        'title', 'description', 'url', 'company_id', 'props'
    ];

    protected static $logName = 'system';

    protected static $logAttributes = [
        'title', 'description', 'url', 'company_id'
    ];

    /**
     * @param Activity $activity
     * @param string $eventName
     */
    public function tapActivity(Activity $activity, string $eventName)
    {
        $description = $this->getDescriptionForEvent($eventName);
        ActivityEvent::dispatch($activity, $description);
    }

    /**
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "A reports has been {$eventName}";
    }

    /**
     * @return bool
     */
    public function updateReports()
    {
        request()->validate([
            'title' => 'required',
            'url' => 'required'
        ]);

        $new_data = [
            'title' => request()->title,
            'description' => request()->description ?? null,
            'url' => request()->url
        ];
        if (request()->url != $this->url || empty($this->props)) {
            $props = (array)$this->props;
            $props = $this->getPreviewArray(request()->url) + $props;
            $new_data['props'] = $props;
        }
        return $this->update($new_data);
    }
}
