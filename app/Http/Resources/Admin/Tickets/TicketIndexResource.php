<?php

namespace App\Http\Resources\Admin\Tickets;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        return parent::toArray($request);
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'status' => $this->status,
            'priority' => $this->priority,

            // relations
            'sender' => $this->sender,
            'receiver' => $this->receiver,

            // time
            'created_at' => verta($this->created_at)->format("l , %d %B %Y"),
        ];
    }
}
