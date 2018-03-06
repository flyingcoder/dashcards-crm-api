<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dashboard;

class DashitemController extends Controller
{
    public function changeOrder($dashboard_id)
    {
    	$dashboard = Dashboard::findOrFail($dashboard_id);

    	$item_sequence = request()->item_sequence;

    	foreach ($dashboard->dashitems as $key => $item) {
    		foreach ($item_sequence as $key => $seq) {
    			if($item->slug == $seq['slug'] && $item->pivot->order != $seq['order']){
    				$dashboard->dashitems()->updateExistingPivot($item->id, ['order' => $seq['order']]);
    			}
    		}
    	}

    	return $item_sequence;
    }
}
