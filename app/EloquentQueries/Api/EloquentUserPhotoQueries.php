<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 20.02.2020
 * Time: 19:12
 */

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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EloquentUserPhotoQueries implements UserPhotoQueries
{


    /**
     *   * Data example
     *  [
     *      0 => base64string
     *      1 => base64string
     *      n => base64string
     *  ]
     * @inheritDoc
     */
    //dbImages = []
    public function addPhotos($userId, $images)
    {
        //todo:refactoring
        $dbImages = [];
        foreach ($images as $image) {

            $imageName = FileHelper::storeBase64File(
                $image,
                config('image.user_photo.save_path')
            );

            $dbImages[] = [
                'name' => $imageName,
                'user_id' => $userId,
                'is_main' => 0
            ];
        }

        try {
            UserPhoto::insert($dbImages);
            return true;
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Error add photos', 503);
        }

    }


    /**
     * @inheritDoc
     */
    public function addPhoto($userId, $image, $isMain = 0)
    {

        if ($isMain) {
            self::zeroingIsMain($userId);
        }

        $imageName = FileHelper::storeBase64File(
            $image,
            config('image.user_photo.save_path')
        );
        try {
            UserPhoto::create([
                'user_id' => $userId,
                'name' => $imageName,
                'is_main' => $isMain
            ]);
            return true;
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Error add photo', 503);
        }

    }

    public function countUserPhoto($id)
    {
        try {
            return UserPhoto::where('user_id', $id)->count();
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot count user photos', 503);

        }

    }

    public function zeroingIsMain($userId)
    {
        try {
            return UserPhoto::where('user_id', $userId)->update(['is_main' => 0]);
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot zeroingIsMain user photos', 503);
        }
    }

    public function assignIsMain($userId)
    {
        $this->zeroingIsMain($userId);
    }

    public function updateIsMain($userId, $data)
    {
        try {
          $this->zeroingIsMain($userId);
            $userPhoto = UserPhoto::whereId($data['photo_id'])->first();
            return $userPhoto->update(['is_main' => 1]);
        } catch (QueryException $exception) {
            Log::warning('QueryException', [$exception]);
            throw new DBActionException('Error update user', 503);
        }
    }

    public function checkIsMain($photoId){
        return (bool)UserPhoto::whereId($photoId)->where('is_main',1)->first();
    }

    public function get($userId){

        return UserPhoto::where('id',$userId)
            ->select('*')
            ->addSelect(DB::raw("CONCAT('".config('image.user_photo.url')."',name) AS url"))
            ->first();
    }

    public function destroy($photoId){
        try{
            $user = UserPhoto::where('id', $photoId)->first();
            return $user->delete();
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot meeting destroy ', 503);
        }
    }

    public function getByUserId($id){
        try{
            return UserPhoto::where('user_id',$id)
                ->select('*')
                ->addSelect(DB::raw("CONCAT('".config('image.user_photo.url')."',name) AS url"))
                ->get();
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot get user photo ', 503);
        }

    }
}














