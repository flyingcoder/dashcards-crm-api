<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
	use SoftDeletes;

	protected $fillable = ['company_id', 'title', 'content', 'remind_date'];

	protected $date = ['deleted_at'];

    public function users()
    {
        return $this->belongsToMany(User::class)
        			->withPivot('is_pinned');
    }

    public function pinning($action)
    {
    	$value = $action == 'pin' ? true : false;

    	return $this->users()
					->updateExistingPivot(
						auth()->user()->id, 
						['is_pinned' => $value]
					);
    }

    public function collaborators()
    {
        return $this->users()->select(
                            'users.first_name',
                            'users.last_name',
                            'users.image_url'
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
