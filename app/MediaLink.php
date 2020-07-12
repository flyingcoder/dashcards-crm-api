<?php

namespace App;

use Spatie\MediaLibrary\Models\Media;

class MediaLink extends Media
{
    protected $table = 'media';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'id', 'model_id');
    }
}
