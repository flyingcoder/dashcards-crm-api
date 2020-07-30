<?php

namespace App;

use App\Traits\HasFileTrait;
use Spatie\Activitylog\Models\Activity as Act;
use Spatie\MediaLibrary\Models\Media;

class Activity extends Act
{
    use HasFileTrait;

    /**
     * @var array
     */
    protected $cast = [
        'read' => 'boolean'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function causer_user()
    {
        return $this->belongsTo(User::class, 'causer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'activity_user', 'activity_id', 'user_id')
            ->withPivot('read_at');
    }

    /**
     * @return array|null
     */
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
