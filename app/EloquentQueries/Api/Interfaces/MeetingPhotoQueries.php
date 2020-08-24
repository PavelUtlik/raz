<?php


namespace App\EloquentQueries\Api\Interfaces;


use App\Models\MeetingPhoto;
use App\Models\MeetingTheme;

interface MeetingPhotoQueries
{

    /**
     * @param $name
     * @return int
     */
    public function create($name);


    /**
     * @param $id int
     * @param $data array
     * @return MeetingPhoto
     */
    public function update($id, $data);

    /**
     * @param $photoId
     * @return mixed
     */

    public function destroy($photoId);

}