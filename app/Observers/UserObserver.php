<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\UserBlocked;
use Illuminate\Support\Facades\Cache;

class UserObserver
{

    public function deleted(User $user)
{
    Cache::forget('user'.$user->id);
}

    public function updated(User $user)
    {
        Cache::forget('user'.$user->id);
        if ($user->isDirty(['is_blocked'])) {
            $user->notify(new UserBlocked());
        }
    }

}
