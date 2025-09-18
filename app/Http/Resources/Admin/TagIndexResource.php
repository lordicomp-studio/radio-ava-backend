<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class TagIndexResource extends JsonResource
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
            'slug' => $this->slug, // this is used in tag suggestion widget in menuSettings page.

            // relations
            'user' => $this->user,

            // time
            'created_at' => verta($this->created_at)->format("l , %d %B %Y"),
        ];
    }
}
