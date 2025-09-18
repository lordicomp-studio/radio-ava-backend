<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\Api\UserInfoResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleShowResource extends JsonResource
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

            'users_count' => $this->users_count,

            // relations
            'users' => UserInfoResource::collection($this->users),
            'permissions' => $this->permissions,
        ];
    }
}
