<?php


namespace App\EloquentQueries\Api;


use App\EloquentQueries\Api\Interfaces\MeetingChatQueries;
use App\Exceptions\DBActionException;
use App\Helpers\MeetingStatuses;
use App\Models\Meeting;
use App\Models\MeetingChat;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EloquentMeetingChatQueries implements MeetingChatQueries
{

    /**
     * @inheritDoc
     */
    public function store($data)
    {
        try {
            return MeetingChat::create($data);

        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot attach user to chat ', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function destroy($uniqueId)
    {
        // TODO: Implement destroy() method.
    }



    /**
     * @inheritDoc
     * @throws DBActionException
     */
    public function attachUsers($senderId, $recipientId, $chat)
    {
        try {
            $chat->users()->attach([$senderId, $recipientId]);
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot attach users to chat ', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function getByUser($userId)
    {
        try {

            return MeetingChat::
                whereHas('users', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->whereHas('meeting', function ($query) use ($userId) {
                    $query->where('meeting_status_code', MeetingStatuses::STARTED);
                })
                ->with(['users' => function($query) use($userId){
                    $query->where('user_id','!=', $userId);
                    $query->with(['photo' => function ($query) {
                        $query->select('*');
                        $query->where('is_main', 1);
                        $query->addSelect(DB::raw("CONCAT('" . config('image.user_photo.url') . "',name) AS url"));
                    }]);
                }])
                ->with(['meeting' => function ($query) {
                    $query->select('id', 'meeting_photo_id', 'latitude', 'longitude', 'meeting_theme_id', 'owner_id', 'end_time');
                    $query->addSelect(DB::raw("(SELECT CONCAT('" . config('image.meeting_photo.url') . "',name) FROM meeting_photos WHERE id=meeting_photo_id) as image_url"));
                    $query->addSelect(DB::raw('(SELECT name FROM meeting_themes WHERE id = meeting_theme_id) as name'));

                    $query->with(['owner' => function ($query) {
                        $query->select('id', 'name', 'interested_filter_id');
                        $query->with(['interestedFilter' => function ($query) {
                            $query->select('id', 'latitude', 'longitude');
                        }]);
                    }]);

                }, 'latestMessage' => function ($query) use ($userId) {
                    $query->withLastMessage();
                }])
                ->withCount(['messages as new_message_count' => function ($query) use ($userId) {
                    $query->where('is_read', 0);
                    $query->where('sender_id', '!=', $userId);
                }])
                ->get();

        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot get chats ', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function detachUser($userId, $chat)
    {
        try {
            $chat->users()->detach($userId);
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot detach user to chat ', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function findByUnique($uniqueId)
    {
        try {
            return MeetingChat::where('unique_id', $uniqueId)->first();
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot meeting theme update ', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function getByIdAndParticipant($chatId, $participantId)
    {
        try {
            return MeetingChat::where('id', $chatId)
                ->withCount('users')
                ->wherehas('users', function ($query) use ($participantId) {
                    $query->whereUserId($participantId);
                })->first();
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Db error', 503);
        }

    }


    /**
     * @inheritDoc
     */
    public function isExist($userId, $meetingId)
    {
        try {
            return (bool)MeetingChat::where('meeting_id', $meetingId)
                ->wherehas('users', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })->first();
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Db error', 503);
        }

    }

    /**
     * @inheritDoc
     */
    public function isChatParticipant($participantId)
    {

    }

    /**
     * @inheritDoc
     */
    public function getParticipants($chatId)
    {
        // TODO: Implement getParticipants() method.
    }

    /**
     * @inheritDoc
     */
    public function findWith($chatId)
    {
        try {
            return MeetingChat::where('id', $chatId)
                ->withCount('users')
                ->first();
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Db error', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function block($chatId, $whoBlockedId)
    {
        try {
            return MeetingChat::whereId($chatId)
                ->update([
                    'is_blocked' => 1,
                    'who_blocked' => $whoBlockedId
                ]);
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Db error', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function unblock($chatId)
    {
        try {
            return MeetingChat::whereId($chatId)
                ->update([
                    'is_blocked' => 0,
                    'who_blocked' => null
                ]);
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Db error', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function findWithParticipants($id)
    {
        try {
            return MeetingChat::with('users')->where('id', $id)->first();
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot meeting theme update ', 503);
        }
    }


    /**
     * @inheritDoc
     */
    public function destroyByMeeting($meetingId)
    {
        try {
            return MeetingChat::where('meeting_id', $meetingId)->delete();
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot delete chats ', 503);
        }
    }

}












