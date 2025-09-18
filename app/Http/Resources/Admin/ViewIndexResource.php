<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ViewIndexResource extends JsonResource
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
            'ip' => $this->ip,
            'device' => $this->device,
            'platform' => $this->platform,
            'browser' => $this->browser,

            // relations
            'user' => $this->user,
            'viewable' => $this->viewable,

            // time
            'created_at' => verta($this->created_at)->format("l , %d %B %Y"),
        ];
    }
}
