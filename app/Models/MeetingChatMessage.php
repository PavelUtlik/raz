<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MeetingChatMessage extends Model
{


    const UNREAD = 0;
    const READ = 1;

    protected $fillable = [
        'message',
        'chat_message_file_id',
        'chat_message_type_id',
        'meeting_chat_id',
        'sender_id',
        'is_read',
        'unique_id'
    ];

    public function meetingChat()
    {
        return $this->belongsTo(MeetingChat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'sender_id');
    }

    public function file()
    {
        return $this->hasOne(ChatMessageFile::class);
    }

    public function type()
    {
        return $this->hasOne(ChatMessageType::class);
    }

    public function scopeWithLastMessage($query)
    {
        return $query
            ->with(['user' => function ($query) {
                $query->select('id','name');
//            $query->with(['photo' => function ($query) {
//                $query->select('*');
//                $query->where('is_main', 1);
//                $query->addSelect(DB::raw("CONCAT('" . config('image.user_photo.url') . "',name) AS url"));
//            }]);
        }]
            )->latest();
    }

}