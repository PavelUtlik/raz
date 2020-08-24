<?php


namespace App\EloquentQueries\Api\Interfaces;


use App\Models\MeetingChatMessage;
use Illuminate\Support\Collection;

interface ChatMessageQueries
{

    /**
     * @param $senderId integer
     * @param $data array
     * @return bool|MeetingChatMessage
     */
    public function create($senderId, $data);


    /**
     * @param $chatId
     * @return Collection|MeetingChatMessage[]
     */
    public function getByChatId($chatId);

    /**
     * @param $senderId
     * @param $chatId
     * @return mixed
     */
    public function markAsRead($senderId, $chatId);

    /**
     * @param $userId integer
     * @return mixed
     */
    public function getUnreadMessages($userId);

    /**
     * @param $messageUniqueIds array|int
     * @return mixed
     */
    public function markAsReadByUniqueId($messageUniqueIds);


    /**
     * @param $meetingId integer
     * @return mixed
     */
    public function destroyByMeeting($meetingId);

    /**
     * @param $meetingId integer
     * @return mixed
     */
    public function countByMeeting($meetingId);

}