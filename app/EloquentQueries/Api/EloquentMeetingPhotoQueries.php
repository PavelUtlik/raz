<?php


namespace App\EloquentQueries\Api;


use App\EloquentQueries\Api\Interfaces\MeetingPhotoQueries;
use App\Exceptions\DBActionException;
use App\Models\MeetingPhoto;
use App\Models\UserPhoto;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class EloquentMeetingPhotoQueries implements MeetingPhotoQueries
{

    public function create($name)
    {
        try {
           $photo =  MeetingPhoto::create([
                'name' => $name,
            ]);
            return $photo->id;
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Error add photo', 503);
        }
    }

    public function update($id, $data)
    {
        try {
            return MeetingPhoto::whereId($id)->update($data);
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot meeting photo update ', 503);
        }
    }

    public function destroy($photoId){
        try{
            return MeetingPhoto::where('id', $photoId)->delete();
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot meeting destroy ', 503);
        }
    }

}