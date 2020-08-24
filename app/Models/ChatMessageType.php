<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ChatMessageType extends Model
{

    public function message()
    {
        return $this->belongsTo(MeetingChatMessage::class);
    }

}