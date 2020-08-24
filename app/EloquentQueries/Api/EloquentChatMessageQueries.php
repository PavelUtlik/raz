<?php


namespace App\EloquentQueries\Api;


use App\EloquentQueries\Api\Interfaces\ChatMessageQueries;
use App\Exceptions\DBActionException;
use App\Models\ChatMessageFile;
use App\Models\MeetingChatMessage;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EloquentChatMessageQueries implements ChatMessageQueries
{

    /**
     * @inheritDoc
     */
    public function create($senderId, $data)
    {
        try {
            $data['sender_id'] = $senderId;
            return MeetingChatMessage::create($data);
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot create chatMessage  ', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function getByChatId($chatId)
    {

        try {
            return MeetingChatMessage::with(['user' => function ($query) {
                $query->with(['photo' => function ($query) {
                    $query->where('is_main', 1);
                    $query->withUrl();
                }]);
            }])->where('meeting_chat_id', $chatId)
                ->orderBy('created_at', 'desc')
                ->paginate(30);
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot get chatMessages ', 503);
        }

    }

    /**
     * @inheritDoc
     */
    public function markAsRead($senderId, $chatId)
    {
        try {
            return MeetingChatMessage::where('sender_id', '!=', $senderId)
                ->where('meeting_chat_id', $chatId)
                ->where('is_read', 0)
                ->update(['is_read' => 1]);
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot update messages ', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function getUnreadMessages($userId)
    {
        try {
            return MeetingChatMessage::select('*')
                ->addSelect(DB::raw(
                    "(SELECT name FROM users WHERE id=sender_id) as sender_name"
                ))
                ->addSelect(DB::raw("(SELECT CONCAT('" . config('image.user_photo.url') . "',name) FROM user_photos WHERE is_main=1 AND user_photos.user_id=sender_id ) AS sender_image_url"))
                ->where('sender_id', '!=', $userId)
                ->where('is_read', MeetingChatMessage::UNREAD)
                ->wherehas('meetingChat', function ($query) use ($userId) {
                    $query->whereHas('users', function ($query) use ($userId) {
                        $query->where('user_id', $userId);
                    });
                })
                ->limit(10)
                ->get();
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot update messages ', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function markAsReadByUniqueId($messageUniqueIds)
    {
        try {

            $query = MeetingChatMessage::query();

            $query = is_array($messageUniqueIds)
                ? $query->whereIn('unique_id', $messageUniqueIds)
                : $query->where('unique_id', $messageUniqueIds);

            return $query->update(['is_read' => 1]);
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot update messages ', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function destroyByMeeting($meetingId)
    {
        try {
            return MeetingChatMessage::whereHas('meetingChat', function ($query) use ($meetingId) {
                $query->where('meeting_id', $meetingId);
            })->delete();
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot delete messages ', 503);
        }
    }

    /**
     * @inheritDoc
     */
    public function countByMeeting($meetingId)
    {
        try {
            return MeetingChatMessage::whereHas('meetingChat', function ($query) use ($meetingId) {
                $query->where('meeting_id', $meetingId);
            })->count();
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);
            throw new DBActionException('Cannot get count messages ', 503);
        }
    }
}






























