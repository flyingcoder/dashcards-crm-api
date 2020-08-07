<?php

namespace App;

use App\Events\ActivityEvent;
use App\Traits\HasUrlTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class Report
 * @package App
 */
class Report extends Model
{
    use SoftDeletes, LogsActivity, HasUrlTrait;

    /**
     * @var int
     */
    protected $paginate = 10;

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array
     */
    protected $casts = ['props' => 'array'];

    /**
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'url', 'company_id', 'props', 'project_id'
    ];

    /**
     * @var array
     */
    public $appends = ['creator'];
    /**
     * @var string
     */
    protected static $logName = 'system';

    /**
     * @var array
     */
    protected static $logAttributes = [
        'title', 'description', 'url', 'company_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return User|\Illuminate\Database\Eloquent\Builder|Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function getCreatorAttribute()
    {
        if (isset($this->props['creator'])) {
            return User::withTrashed()->where('id', $this->props['creator'])->first();
        }
        return null;
    }

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
