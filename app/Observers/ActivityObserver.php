<?php

namespace App\Observers;

use Spatie\Activitylog\Models\Activity;

class ActivityObserver
{
    /**
     * Handle the activity "created" event.
     *
     * @param  Spatie\Activitylog\Models\Activity  $activity
     * @return void
     */
    public function created(Activity $activity)
    {
        if (isset($activity->properties['attributes'])) {
            $props = array_keys($activity->properties['attributes']);
            $type = explode('\\', $activity->subject_type)[1] ?? '';
            $desc = $type." ".natural_language_join($props)." updated";
            $activity->description = $desc;
        }
        $activity->properties = $activity->properties->put('ip', request()->ip());
        $activity->save();
    }

    /**
     * Handle the activity "updated" event.
     *
     * @param  Spatie\Activitylog\Models\Activity  $activity
     * @return void
     */
    public function updated(Activity $activity)
    {
        //
    }

    /**
     * Handle the activity "deleted" event.
     *
     * @param  Spatie\Activitylog\Models\Activity  $activity
     * @return void
     */
    public function deleted(Activity $activity)
    {
        //
    }

    /**
     * Handle the activity "restored" event.
     *
     * @param  Spatie\Activitylog\Models\Activity  $activity
     * @return void
     */
    public function restored(Activity $activity)
    {
        //
    }

    /**
     * Handle the activity "force deleted" event.
     *
     * @param  Spatie\Activitylog\Models\Activity  $activity
     * @return void
     */
    public function forceDeleted(Activity $activity)
    {
        //
    }
}
