<?php

namespace App\EloquentQueries\Api;


use App\EloquentQueries\Api\Interfaces\UserQueries;
use App\Exceptions\DBActionException;
use App\Exceptions\ErrorImplementServiceMethodException;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;


class EloquentUserQueries implements UserQueries
{

    /**
     * @inheritdoc
     */
    public function create($data)
    {
        try {
            $data['password'] = bcrypt($data['password']);
            $user = User::create($data);

            if (empty($user->id)) {
                Log::warning('Cannot user create');
                throw new DBActionException('Cannot create user', 503);
            }

            return $user;
        } catch (QueryException $queryException) {
            Log::warning('Cannot user create', [$queryException]);
            throw new DBActionException('Cannot user create ', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function find($id)
    {
        try {
            return Cache::rememberForever('user'.$id, function () use ($id) {
                return User::with(['gender', 'interestedFilter', 'photo' => function ($query) use ($id) {
                    $query->select('*');
                    $query->addSelect(DB::raw("CONCAT('" . config('image.user_photo.url') . "',name) AS url"));
                }])->whereId($id)->first();
            });
        } catch (QueryException $queryException) {
            Log::warning('Error get user', [$queryException]);
            throw new DBActionException('Error get user', 503);
        }
    }


    /**
     * @inheritDoc
     */
    public function update($id, $data)
    {
        try {
            return User::find($id)->update($data);
        } catch (QueryException $queryException) {
            Log::warning('Error update user', [$queryException]);
            throw new DBActionException('Error update user', 503);
        }
    }

    public function checkVip($id)
    {
        try {
            $user = User::whereId($id)->first();
            if (!$user) {
                throw new DBActionException('User not found', 503);
            }
            return (bool)$user->is_vip;
        } catch (QueryException $queryException) {
            Log::warning('Error checkVip user', [$queryException]);
            throw new DBActionException('Error checkVip user', 503);
        }
    }
}





User::where('id',2)->get();























