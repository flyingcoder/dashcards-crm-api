<?php

namespace App;

use App\Comment;
use Spatie\MediaLibrary\Media as SpatieMedia;

class Media extends SpatieMedia
{
	public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}