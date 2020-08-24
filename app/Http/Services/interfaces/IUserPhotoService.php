<?php


namespace App\Http\Services\interfaces;


use App\Models\User;

interface IUserPhotoService
{
    /**
     * @param $data array
     * @param int $isMain
     * @return bool
     */
    public function addPhoto($data, $isMain = 0);

    /**
     * @param $data
     * @return bool
     */
    public function addPhotos($data);

    /**
     * @param $userId
     * @param $data
     * @return bool
     */
    public function updatePhoto($userId,$data);

    /**
     * @param $id
     * @return bool
     */

    public function delete($id);
}