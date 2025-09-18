<?php

namespace App\Helpers;

use App\Enums\UserLevelEnum;
use App\Events\MessageCreated;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class TicketHelper
{
    public static function uploadFile($file, $chatId) : string{
        $path = Config::get('constants.ticketsPath');
        $settingsFoldersPath = "/chat-" . $chatId; // adds chatId to foldering path
        $settingsFoldersPath .= SettingsHelper::makePathAccordingToSettings($file);

        $name = FileManager::makeNewFileName(
            $file->getClientOriginalName(), $file->getClientOriginalExtension(), $path . $settingsFoldersPath
        );

        $file->move($path . $settingsFoldersPath, $name);

        return $settingsFoldersPath . $name;
    }

    public static function canUserContact(UserLevelEnum $senderLevel, UserLevelEnum $receiverLevel) : bool{
        // admins can message everyone and everyone can message admins
        if ($senderLevel === UserLevelEnum::Admin
            || $receiverLevel === UserLevelEnum::Admin) return true;

        // clients can message sellers
        if ($senderLevel === UserLevelEnum::Client
            && $receiverLevel === UserLevelEnum::Seller) return true;

        // otherwise
        return false;
    }

    public static function canUserAccessChat(Authenticatable $user, int $chatId) : Chat | null
    {
        // admins can access all chats
        if ($user->level === UserLevelEnum::Admin)
            return Chat::find($chatId);

        // users have to be the receiver or sender of the chat to access it
        $chat = Chat::where('sender_id', $user->id)
            ->where('id', $chatId)
            ->orWhere('receiver_id', $user->id)
            ->where('id', $chatId)
            ->first();

        return $chat ?? null;
    }

    public static function createNewMessage(int $chatId, int $senderId, string $message, $file, ?int $replyToId = null): Message
    {
        if (isset($file)) {
            $fileUrl = TicketHelper::uploadFile($file, $chatId);
        }

        $message = Message::create([
            'chat_id' => $chatId,
            'sender_id' => $senderId,
            'message' => $message,
            'file_url' => $fileUrl ?? null,
            'reply_to_id' => $replyToId,
        ]);

        MessageCreated::dispatch($message);

        return $message;
    }

    public static function getUserUnseenMessageCount(int $userId) : int
    {
        $query = DB::table('chats')
            ->join('messages', 'chats.id', '=', 'messages.chat_id')
            ->where([
                ['messages.sender_id', '!=', $userId],
                ['seen_at', '=', null]
            ]);

        if (User::find($userId)->level !== UserLevelEnum::Admin){
            $query = $query->where(function ($query) use ($userId) {
                $query->where('chats.sender_id', $userId)
                    ->orWhere('chats.receiver_id', $userId);
            });
        }

        return $query->count();
    }

}
