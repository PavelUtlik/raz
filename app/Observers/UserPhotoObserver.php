<?php

namespace App\Observers;


use App\Models\UserPhoto;
use App\Notifications\UserBlocked;
use Illuminate\Support\Facades\Cache;

class UserPhotoObserver
{

    public function deleted(UserPhoto $userPhoto)
    {
        Cache::forget('user'.$userPhoto->user_id);
        if ($userPhoto->isDirty(['is_blocked'])) {
            $userPhoto->notify(new UserBlocked());
        }

    }

    public function updated(UserPhoto $userPhoto)
    {
        Cache::forget('user'.$userPhoto->user_id);
        if ($userPhoto->isDirty(['is_blocked'])) {
            $userPhoto->notify(new UserBlocked());
        }
    }
    public function created(UserPhoto $userPhoto)
    {
        Cache::forget('user'.$userPhoto->user_id);
        if ($userPhoto->isDirty(['is_blocked'])) {
            $userPhoto->notify(new UserBlocked());
        }
    }
 
}