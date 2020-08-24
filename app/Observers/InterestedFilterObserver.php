<?php

namespace App\Observers;

use App\Models\InterestedFilter;
use App\Notifications\UserBlocked;
use Illuminate\Support\Facades\Cache;

class InterestedFilterObserver
{

    public function deleted(InterestedFilter $filter)
    {
        Cache::forget('user'.$filter->user_id);
    }

    public function updated(InterestedFilter $filter)
    {
        Cache::forget('user'.$filter->user_id);
//        if ($user->isDirty(['is_blocked'])) {
//            $user->notify(new UserBlocked());
//        }
    }

}