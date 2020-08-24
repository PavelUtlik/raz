<?php


use App\EloquentQueries\Api\Interfaces\MeetingChatQueries;
use App\Jobs\MarkAsReadMessages;
use App\Models\MeetingChat;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::routes(['prefix' => 'api', 'middleware' => 'auth:api']);

Broadcast::channel('meeting-chat.{meeting_chat_id}', function ($user, $meeting_chat_id) {
    /**
     * Проверки:
     * 1. Не заблокирован ли пользователь
     * 2. Может ли пользователь прослушивать канал +
     */

    $meetingChatQueries = app()->make(MeetingChatQueries::class);

    $chat = $meetingChatQueries->getByIdAndParticipant($meeting_chat_id, $user->id);

    if (!(bool)$chat || $user->is_blocked === \App\Models\User::BLOCKED) {
        Log::info('User ' . $user->id . ' not found in ' . $meeting_chat_id . ' chat');
        return false;
    }

    MarkAsReadMessages::dispatch($user->id, $meeting_chat_id);


    return true;

});

Broadcast::channel('notifications.{user_id}', function ($user, $user_id) {


    if ($user->is_blocked === \App\Models\User::BLOCKED || $user->id !== (int)$user_id) {
        return false;
    }


    return true;
});

//
//Broadcast::channel('meeting-chats.{user_id}', function ($user, $user_id) {
//
//    if ( $user->is_blocked === \App\Models\User::BLOCKED || $user->id !== (int)$user_id ) {
//        return false;
//    }
//
//    return true;
//});
//











