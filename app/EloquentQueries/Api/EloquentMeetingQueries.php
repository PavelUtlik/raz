<?php

namespace App\EloquentQueries\Api;

use App\EloquentFilters\EloquentMeetingFilter;
use App\EloquentQueries\Api\Interfaces\inte;
use App\EloquentQueries\Api\Interfaces\MeetingQueries;
use App\Exceptions\DBActionException;
use App\Helpers\UserMeetingStatuses;
use App\Models\Meeting;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EloquentMeetingQueries implements MeetingQueries
{


    /**
     * @inheritDoc
     */
    public function search($filter, $ownerId = null)
    {
        return (new EloquentMeetingFilter($filter))->get($ownerId);
    }

    /**
     * @inheritDoc
     */
    public function create($data)
    {
        try {
            return Meeting::create($data);
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot create ', 503);
        }
    }




    /**
     * @inheritDoc
     */
    public function destroy($meetingId)
    {

        try {

            return Meeting::where('id', $meetingId)->delete();
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot meeting destroy ', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function update($id, $data)
    {
        try {
            return Meeting::whereId($id)->update($data);
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot meeting theme update ', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function find($id)
    {
        try {

            return Meeting::whereId($id)->first();
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot meeting theme update ', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function findWith($id, $relations)
    {
        try {
            return Meeting::whereId($id)->with($relations)->first();
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot meeting theme update ', 503);
        }
    }

    public function addTimeToMeeting($id, $minutes)
    {
        try {

            return Meeting::where('id', $id)->update([
                'end_time' => DB::raw("DATE_ADD(end_time, INTERVAL $minutes MINUTE)"),
                'time_extension_counter' => DB::raw('time_extension_counter + 1')
            ]);

        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot meeting theme update ', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function get($meeting_id)
    {
        try {

            return Meeting::with('photo')->where('id', $meeting_id)->first();

        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot meeting theme update ', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function updateTheme($meeting_id, $data)
    {
        // TODO: Implement updateTheme() method.
    }

    /**
     * @inheritDoc
     */
    public function updatePhoto($meeting_id, $photo)
    {
        // TODO: Implement updatePhoto() method.
    }


    /**
     * @inheritDoc
     */
    public function getWithChats($meeting_id)
    {
        // TODO: Implement getWithChats() method.
    }

    /**
     * @inheritDoc
     */
    public function findActiveByOwner($ownerId)
    {
        try {
            return Meeting::with(['status', 'theme', 'photo' => function ($query) {
                $query->select('*');
                $query->addSelect(DB::raw("CONCAT('" . config('image.meeting_photo.url') . "',name) AS url"));
            }])
                ->active()
                ->activeStatus()
                ->orderBy('updated_at', 'desc')
                ->where('owner_id', $ownerId)
                ->first();

        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot meeting theme update ', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function getExpired()
    {
        try {
            return Meeting::expired()->activeStatus()->get();

        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot meeting theme update ', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function getActiveCountByOwner($ownerId)
    {
        try {
            return Meeting::
            active()
                ->orderBy('updated_at', 'desc')
                ->where('owner_id', $ownerId)
                ->count();

        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot meeting theme update ', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function changeStatus($id, $statusCode)
    {
        try {
            return Meeting::whereId($id)->update([
                'meeting_status_code' => $statusCode
            ]);
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot meeting update ', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function findActive($id)
    {
        try {
            return Meeting::
            activeStatus()
                ->active()
                ->whereId($id)
                ->first();

        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot get meeting', 503);
        }
    }

    public function countDeletedMessages()
    {
        try {
            return Meeting::sum('deleted_messages_counter');

        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot get meeting', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function increment($meetingId, $fieldName)
    {
        try {
            return Meeting::whereId($meetingId)->increment($fieldName);
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Error increment ' . $fieldName . ' field', 503);
        }
    }
}



























