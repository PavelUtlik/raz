<?php

namespace App\Models;

use App\Observers\UserObserver;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    const NOT_BLOCKED = 0;
    const BLOCKED = 1;

    protected $fillable = [
        'name',
        'email',
        'password',
        'last_time_create_meeting',
        'interested_filter_id',
        'date_of_birth',
        'gender_code',
        'is_blocked'
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function gender()
    {

        return $this->belongsTo(Gender::class, 'gender_code', 'code');
    }

    public function interestedFilter()
    {
        return $this->belongsTo(InterestedFilter::class);
    }

    

    public function ownerMeetings()
    {
        return $this->hasMany(Meeting::class, 'owner_id');
    }


    public function photo()
    {
        return $this->hasMany(UserPhoto::class);
    }


    public function messages()
    {
        return $this->hasMany(MeetingChatMessage::class, 'sender_id');
    }

    public function receivesBroadcastNotificationsOn()
    {
        return 'notifications.' . $this->id;
    }

}


























