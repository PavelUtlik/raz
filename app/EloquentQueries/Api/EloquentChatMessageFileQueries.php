<?php


namespace App\EloquentQueries\Api;


use App\EloquentQueries\Api\Interfaces\ChatMessageFileQueries;
use App\Exceptions\DBActionException;
use App\Models\ChatMessageFile;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

class EloquentChatMessageFileQueries implements ChatMessageFileQueries
{


    /**
     * @inheritDoc
     */
    public function create($senderId, $data)
    {
        try {
            $data['sender_id'] = $senderId;
            return ChatMessageFile::create($data);
        } catch (QueryException $queryException) {
            Log::warning('Cannot create chatMessage file ', [$queryException]);
            throw new DBActionException('Cannot create chatMessage file ', 503);
        }
    }


    /**
     * @inheritDoc
     */
    public function getByMeeting($meetingId)
    {
        try {
            return ChatMessageFile::whereHas('message', function ($query) use ($meetingId) {
                $query->whereHas('meetingChat', function ($query) use ($meetingId) {
                    $query->where('meeting_id', $meetingId);
                });
            })->get();
        } catch (QueryException $queryException) {
            Log::warning('getByMeeting exception', [$queryException]);
            throw new DBActionException('getByMeeting exception', 503);
        }

    }

    /**
     * @inheritDoc
     */
    public function destroy($ids)
    {
        try {
            return ChatMessageFile::destroy($ids);
        } catch (QueryException $queryException) {
            Log::warning('Cannot destroy chatMessage files', [$queryException]);
            throw new DBActionException('Cannot destroy chatMessage files ', 503);
        }
    }
}



















