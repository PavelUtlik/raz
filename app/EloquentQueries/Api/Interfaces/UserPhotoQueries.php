<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 20.02.2020
 * Time: 19:12
 */

namespace App\EloquentQueries\Api\Interfaces;


use App\Exceptions\DBActionException;
use App\Models\UserPhoto;

interface UserPhotoQueries
{


    /**
     * @param $userId int
     * @param $images
     * @throws DBActionException
     * @return bool
     */
    public function addPhotos($userId, $images);

    /**
     * @param $userId int
     * @param $data string
     * @throws DBActionException
     * @param  $isMain bool
     * @return bool|string
     */
    public function addPhoto($userId, $data, $isMain = false);

    /**
     * @throws DBActionException
     * @param $id int
     * @return int
     */
    public function countUserPhoto($id);
    /**
     * @throws DBActionException
     * @param $userId int
     * @return bool
     */
    public function zeroingIsMain($userId);

    /**
     * @param $userId
     * @param $data
     * @return bool
     */

    public function updateIsMain($userId, $data);

    /**
     * @param $photoId
     * @return bool
     */

    public function checkIsMain($photoId);

    /**
     * @throws DBActionException
     * @param $userId int
     * @return UserPhoto|bool
     */
    public function get($userId);

    /**
     * @param $photoId
     * @return bool
     */

    public function destroy($photoId);

    /**
     * @param $id
     * @return UserPhoto
     */

    public function getByUserId($id);

}