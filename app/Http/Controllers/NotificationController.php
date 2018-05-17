<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Activity;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function unread()
    {
    	return auth()->user()->unreadActivity();
    }

    public function unreadcount()
    {
    	return count(auth()->user()->unreadActivity());
    }

    public function markRead($id)
    {
    	$activity = auth()->user()
    					  ->acts()
    					  ->where('activity_id', $id)
    					  ->first();

    	$activity->pivot->read_at = Carbon::now();

    	if($activity->pivot->save())
			return response(200);
		else
			return response(500);
    }
}
