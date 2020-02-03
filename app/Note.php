<?php

namespace App;

use App\Events\ActivityEvent;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Note extends Model
{
	use SoftDeletes, LogsActivity;

	protected $fillable = ['company_id', 'title', 'content', 'remind_date'];

	protected $date = ['deleted_at'];
    
    protected static $logName = 'system';

    protected static $logAttributes = ['company_id', 'title', 'content', 'remind_date'];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A note has been {$eventName}";
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $description = $this->getDescriptionForEvent($eventName);
        ActivityEvent::dispatch($activity, $description);
    }


    public function users()
    {
        return $this->belongsToMany(User::class)
        			->withPivot('is_pinned');
    }

    public function pinning($action)
    {
    	$value = $action === 'pin' ? true : false;

    	$this->users()
			 ->updateExistingPivot(
				auth()->user()->id, 
				['is_pinned' => $value]
			 );

        return (int) $value;
    }

    public function collaborators()
    {
        return $this->users()->select(
                            'users.id',
                            'users.image_url',
                            DB::raw('CONCAT(CONCAT(UCASE(LEFT(users.last_name, 1)), SUBSTRING(users.last_name, 2)), ", ", CONCAT(UCASE(LEFT(users.first_name, 1)), SUBSTRING(users.first_name, 2))) AS name')
                         )->get();
    }

    public function updateNote()
    {
    	request()->validate([ 'content' => 'required' ]);

    	$this->title = request()->title;

    	$this->content = request()->content;

    	$this->remind_date = request()->remind_date;

    	$this->save();

    	$this->collaborators = $this->collaborators();

    	return $this;
    }
}
