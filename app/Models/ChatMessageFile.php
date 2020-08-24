<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ChatMessageFile extends Model
{

    protected $fillable = ['sender_id', 'name'];

    public function message()
    {
        return $this->belongsTo(MeetingChatMessage::class,'id','chat_message_file_id');
    }

}