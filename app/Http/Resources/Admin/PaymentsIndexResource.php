<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentsIndexResource extends JsonResource
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
            'track_id' => $this->track_id,
            'code' => $this->code,
            'price' => $this->price,
            'status' => $this->status,

            // relations
            'payer' => $this->payer,

            // time
            'created_at' => verta($this->created_at)->format("l , %d %B %Y"),
        ];
    }
}
