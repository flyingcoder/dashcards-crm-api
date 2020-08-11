<?php

namespace App;

use App\Events\ActivityEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Note extends Model
{
    use SoftDeletes, LogsActivity;

    /**
     * @var array
     */
    protected $fillable = ['company_id', 'title', 'content', 'remind_date'];

    /**
     * @var array
     */
    protected $date = ['deleted_at'];

    /**
     * @var string
     */
    protected static $logName = 'system';

    /**
     * @var array
     */
    protected static $logAttributes = ['company_id', 'title', 'content', 'remind_date'];

    /**
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "A note has been {$eventName}";
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('is_pinned');
    }

    /**
     * @param $action
     * @return int
     */
    public function pinning($action)
    {
        $value = $action === 'pin' ? true : false;

        $this->users()
            ->updateExistingPivot(
                auth()->user()->id,
                ['is_pinned' => $value]
            );

        return (int)$value;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function collaborators()
    {
        return $this->users()->select(
            'users.id',
            'users.image_url',
            DB::raw('CONCAT(CONCAT(UCASE(LEFT(users.last_name, 1)), SUBSTRING(users.last_name, 2)), ", ", CONCAT(UCASE(LEFT(users.first_name, 1)), SUBSTRING(users.first_name, 2))) AS name')
        )->get();
    }

    /**
     * @return $this
     */
    public function updateNote()
    {
        request()->validate(['content' => 'required']);

        $this->title = request()->title;
        $this->content = request()->get('content');
        $this->remind_date = request()->remind_date;
        $this->save();
        $this->collaborators = $this->users;//collaborators();
        return $this;
    }
}
