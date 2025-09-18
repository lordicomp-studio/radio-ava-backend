<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ArtistIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,

            // relations
            'photo' => $this->photo,
            'country' => $this->country,

            // time
            'birth_date' => isset($this->birth_date) ? verta($this->birth_date)->format("l , %d %B %Y") : '-',
            'created_at' => verta($this->created_at)->format("l , %d %B %Y"),
        ];
    }
}
