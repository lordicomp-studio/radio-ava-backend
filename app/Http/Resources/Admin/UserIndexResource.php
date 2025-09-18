<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class UserIndexResource extends JsonResource
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
            'email' => $this->email,
            'level' => $this->level,

            // relations
            'profilePhoto' => $this->profilePhoto,
            'role' => $this->roles()->first(),

            // time
            'email_verified_at' => $this->email_verified_at,
            'created_at' => verta($this->created_at)->format("l , %d %B %Y"),
        ];
    }
}
