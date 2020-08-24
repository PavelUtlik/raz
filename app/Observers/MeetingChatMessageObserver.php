<?php


namespace App\Observers;


use App\Models\MeetingChatMessage;

class MeetingChatMessageObserver
{

    public function creating(MeetingChatMessage $chatMessage)
    {
        $chatMessage->unique_id = uniqid();
    }

}