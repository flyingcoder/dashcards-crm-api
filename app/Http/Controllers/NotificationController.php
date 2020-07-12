<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * @return mixed
     */
    public function unread()
    {
        return auth()->user()->unreadActivity();
    }

    /**
     * @return int
     */
    public function unreadcount()
    {
        return count(auth()->user()->unreadActivity());
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function markRead($id)
    {
        $activity = auth()->user()
            ->acts()
            ->where('activity_id', $id)
            ->first();

        $activity->pivot->read_at = Carbon::now();

        if ($activity->pivot->save())
            return response(200);
        else
            return response(500);
    }
}
