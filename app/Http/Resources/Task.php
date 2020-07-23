<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Task extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'milestone_id' => $this->milestone_id,
            'status' => $this->status,
            'days' => $this->days,
            'total_time' => $this->total_time,
            'assigned' => $this->assigned,
            'description' => $this->description,
            'started_at' => $this->started_at,
            'end_at' => $this->end_at
        ];
    }
}
