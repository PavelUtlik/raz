<?php


namespace App\EloquentQueries\Api;


use App\EloquentQueries\Api\Interfaces\MeetingThemeQueries;
use App\Exceptions\DBActionException;
use App\Models\MeetingTheme;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class EloquentMeetingThemeQueries implements MeetingThemeQueries
{

    /**
     * @inheritDoc
     */
    public function create($data)
    {

        try {
            return MeetingTheme::create($data);
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);

            throw new DBActionException('Cannot meeting theme create ', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function update($id, $data)
    {
        try {
            return MeetingTheme::whereId($id)->update($data);
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot meeting theme update ', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function destroy($id)
    {
        try {
            return MeetingTheme::whereId($id)->delete();
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot user create ', 503);
        }
    }

    public function get($id){
        try {
            return MeetingTheme::where("user_id",$id)->orWhere('user_id',null)->get();
           // return MeetingTheme::whereRaw("user_id = $id or user_id is null")->get();
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot get Meeting Theme ', 503);
        }

    }
}