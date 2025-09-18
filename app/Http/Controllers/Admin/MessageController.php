<?php

namespace App\Http\Controllers\Admin;

use App\Events\MessageCreated;
use App\Events\MessageEdited;
use App\Helpers\FileManager;
use App\Helpers\SettingsHelper;
use App\Helpers\TicketHelper;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class MessageController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'chatId' => 'required | integer',
            'message' => 'required_without:file | min:2',
            'file' => 'nullable | max:5000',
        ]);

        if (isset($fields['file'])){
            $fileUrl = TicketHelper::uploadFile($request->file('file'), $fields['chatId']);
        }

        // because this is in admin panel. we don't check if admin can message this chat. admins can message in all chats.
        $message = Message::create([
            'chat_id' => $fields['chatId'],
            'sender_id' => auth()->id(),
            'message' => $fields['message'],
            'file_url' => $fileUrl ?? null,
        ]);

        MessageCreated::dispatch($message);

        return response(null, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        $fields = $request->validate([
            'message' => 'required | min:2',
        ]);

        $message->update([
            'message' => $fields['message'],
        ]);

        MessageEdited::dispatch($message);

        return response(null, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        $message->delete();
        return response(null, 200);
    }
}
