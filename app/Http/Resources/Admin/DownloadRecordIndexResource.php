<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DownloadRecordIndexResource extends JsonResource
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

            // relations
            'user' => $this->user,
            'downloadable' => $this->downloadable,

            // time
            'created_at' => verta($this->created_at)->format("l , %d %B %Y"),
        ];
    }
}
