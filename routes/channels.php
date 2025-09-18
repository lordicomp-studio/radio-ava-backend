<?php

use App\Enums\UserLevelEnum;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

//Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});

Broadcast::channel('Chats.{id}', function ($user, $id) {
    $chat = \App\Models\Chat::find($id);

    return $user->level === UserLevelEnum::Admin
        || (int) $chat->sender_id === (int) $user->id
        || (int) $chat->receiver_id === (int) $user->id;
});

Broadcast::channel('Users.{id}.ChatList', function ($user, $id){
    return (int) $user->id === (int) $id;
});

Broadcast::channel('Admins.ChatList', function ($user){
    return $user->level === UserLevelEnum::Admin;
});
