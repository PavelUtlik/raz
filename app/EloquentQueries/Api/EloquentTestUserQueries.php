<?php


namespace App\EloquentQueries\Api;

use App\EloquentQueries\Api\Interfaces\UserPhotoQueries;
use App\Exceptions\DBActionException;
use App\Helpers\FileHelper;
use App\Helpers\ImageHelper;
use App\Helpers\UserPhotoCodes;
use App\Models\Meeting;
use App\Models\User;
use App\Models\UserPhoto;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;


class EloquentTestUserQueries
{
    public function getUser($id){
        try {

            return Cache::rememberForever('user'.$id, function () use ($id) {
                return User::with(['gender', 'interestedFilter', 'photo' => function ($query) use ($id) {
                    $query->select('*');
                    $query->addSelect(DB::raw("CONCAT('" . config('image.user_photo.url') . "',name) AS url"));
                }])->whereId($id)->first();
            });



//            \Cache::rememberForever('users', function ($id) {
//                dd($id);
//                return User::with(['gender', 'interestedFilter', 'photo' => function ($query) use ($id) {
//                    $query->select('*');
//                    $query->addSelect(DB::raw("CONCAT('" . config('image.user_photo.url') . "',name) AS url"));
//                }])->whereId($id)->first();
//            });

        } catch (QueryException $queryException) {
            Log::warning('Error get user', [$queryException]);
            throw new DBActionException('Error get user', 503);
        }
    }


    public function update($data){
        try {
            return User::update($data);
        } catch (QueryException $queryException) {
            Log::warning('Error update user', [$queryException]);
            throw new DBActionException('Error update user', 503);
        }
    }
}