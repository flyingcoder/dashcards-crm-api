<?php

namespace App;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Form extends Model
{
	use SoftDeletes,
		Sluggable;

    protected $fillable = ['title', 'status', 'questions'];

    protected $dates = ['deleted_at'];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public static function store(Request $request)
    {

    	request()->validate([
    		'questions' => 'required',
    		'title' => 'required'
    	]);

    	return Auth::user()->forms()->create([
    		'title' => $request->title,
    		'questions' => $request->questions,
    		'status' => 'Enabled'
    	]);
    }
}
