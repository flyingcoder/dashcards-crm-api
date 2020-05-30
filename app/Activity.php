<?php

namespace App;

use App\Traits\HasFileTrait;
use Spatie\Activitylog\Models\Activity as Act;
use Spatie\MediaLibrary\Models\Media;

class Activity extends Act
{
	use HasFileTrait;

	protected $cast = [
		'read' => 'boolean'
	];

    public function users()
    {
    	return $this->belongsToMany(User::class, 'activity_user', 'activity_id', 'user_id')
    				->withPivot('read_at');
    }

    public function attachments()
    {	
    	if (is_array($this->properties['media'])) {    		
	    	$data = [];
	    	$medias = $this->properties['media'];
	    	foreach ($medias as $key => $media) {
	    		$media = Media::find($media['id']);
	    		$data[] = $this->getFullMedia($media);
	    	}
	    	return $data;
    	}
    	return null;
    }
}
