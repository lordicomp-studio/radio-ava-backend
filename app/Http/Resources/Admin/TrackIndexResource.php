<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrackIndexResource extends JsonResource
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
            'description' => $this->description,
            'duration' => $this->duration,
            'is_mv' => $this->is_mv,
            'lyrics_file_url' => $this->lyrics_file_url,

            // relations
            'uploader' => $this->uploader,
            'cover' => $this->cover,
            'artist' => $this->artist,
            'album' => $this->album,
            'country' => $this->country,

            // time
            'release_date' => isset($this->release_date) ? verta($this->release_date)->format("l , %d %B %Y") : '-',
            'created_at' => verta($this->created_at)->format("l , %d %B %Y"),
        ];
    }
}
