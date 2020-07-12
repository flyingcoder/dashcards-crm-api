<?php

namespace App\Traits;

trait HasMediaLink
{

    /**
     * @param $dataArray
     * @return mixed
     */
    public function createMediaLink($dataArray)
    {
        return $this->mediaLinks()->create([
            'model_type' => get_class($this),
            'model_id' => $this->id,
            'collection_name' => 'project.files.links',
            'name' => $dataArray['name'] ?? '',
            'file_name' => $dataArray['file_name'] ?? '',
            'mime_type' => $dataArray['mime_type'] ?? 'link',
            'disk' => $dataArray['disk'] ?? 'public',
            'manipulations' => $dataArray['manipulations'] ?? [],
            'custom_properties' => $dataArray['custom_properties'] ?? [],
            'size' => $dataArray['size'] ?? 0,
            'created_at' => now()->format('Y-m-d H:i:s')
        ]);
    }

}