<?php

namespace App\Http\Resources\Admin\Tickets;

use App\Helpers\FileManager;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketShowResource extends JsonResource
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
            'subject' => $this->subject,
            'status' => $this->status,
            'priority' => $this->priority,
            'sender' => [
                'id' => $this->sender?->id,
                'name' => $this->sender?->name,
                'email' => $this->sender?->email,
                'profilePhotoUrl' => $this->sender?->profilePhoto?->url,
            ],
            'receiver' => [
                'id' => $this->receiver?->id,
                'name' => $this->receiver?->name,
                'email' => $this->receiver?->email,
                'profilePhotoUrl' => $this->receiver?->profilePhoto?->url,
            ],
            'messages' => $this->messages->map(function ($message){
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'file' => FileManager::getMessageFileInfo($message),
                    'sender' => [
                        'name' => $message->sender?->name,
                        'profilePhotoUrl' => $message->sender?->profilePhoto?->url,
                    ],
                    'created_at' => verta($message->created_at)->format("l , %d %B %Y"),
                    'seen_at' => $message->seen_at?->format("H:i l, d F Y"),
                ];
            }),

        ];
    }

}
