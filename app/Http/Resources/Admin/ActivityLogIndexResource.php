<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'event' => $this->event,
            'subject_type' => $this->subject_type,

            // relations
            'causer' => $this->causer,
            'subject' => $this->subject,

            // time
            'created_at' => verta($this->created_at)->format("l , %d %B %Y"),
        ];


        return parent::toArray($request);
    }
}
