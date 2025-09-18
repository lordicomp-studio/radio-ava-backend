<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlbumIndexResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,

            // relations
            'creator' => $this->creator,
            'photo' => $this->photo,
            'artist' => $this->artist,

            // time
            'release_date' => isset($this->release_date) ? verta($this->release_date)->format("l , %d %B %Y") : '-',
            'created_at' => verta($this->created_at)->format("l , %d %B %Y"),
        ];
    }
}
