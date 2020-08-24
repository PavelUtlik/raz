<?php


namespace App\Models;


use App\Helpers\MeetingStatuses;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{

    const MAX_EXTENDED_TIME_COUNT = 4;
    const NOT_VIP_LIFETIME_IN_HOURS = 1;
    const VIP_LIFETIME_IN_HOURS = 2;


    protected $fillable = [
        'owner_id',
        'latitude',
        'longitude',
        'end_time',
        'meeting_theme_id',
        'meeting_photo_id',
        'meeting_status_code',
        'time_extension_counter',
        'deleted_messages_counter'
    ];

    public function owner()
    {
        return $this->hasOne(User::class, 'id', 'owner_id');
    }



    public function theme()
    {
        return $this->hasOne(MeetingTheme::class, 'id', 'meeting_theme_id');
    }

    public function photo()
    {
        return $this->hasOne(MeetingPhoto::class, 'id', 'meeting_photo_id');
    }

    public function status()
    {
        return $this->hasOne(MeetingStatus::class, 'code', 'meeting_status_code');
    }

    public function messages()
    {
        return $this->hasManyThrough(MeetingChatMessage::class, MeetingChat::class,'meeting_id', 'meeting_chat_id', 'id');
    }

    public function ownerGender(){
        return $this->hasOneThrough(Gender::class,User::class,'id','code','owner_id','gender_code');
    }

    public function chats()
    {
        return $this->hasMany(MeetingChat::class);
    }

    public function scopeCloseTo(Builder $query, $latitude, $longitude, $maxRange)
    {
        return $query->whereRaw("
           ST_Distance_Sphere(
                point(longitude, latitude),
                point(?, ?)
            ) / 1000 < ?
        ", [
                $longitude,
                $latitude,
                $maxRange
            ]);
    }

    public function scopeExpired(Builder $query)
    {
        return $query->where('end_time','<', \Carbon\Carbon::now()->toDateTimeString());
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('end_time','>', \Carbon\Carbon::now()->toDateTimeString());
    }

    public function scopeActiveStatus(Builder $query)
    {
        return $query->where('meeting_status_code', MeetingStatuses::STARTED);
    }

    public function scopeIgnoreOwner(Builder $query, $ownerId)
    {
        return $query->where('owner_id','!=', $ownerId);
    }

}


















