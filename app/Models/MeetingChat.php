<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MeetingChat extends Model
{

    protected $fillable = [
        'meeting_id',
        'unique_id',
        'is_blocked',
        'who_blocked'
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(MeetingChatMessage::class);
    }

    public function latestMessage()
    {
        return $this->hasone(MeetingChatMessage::class);
    }

}










