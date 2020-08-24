<?php


namespace App\EloquentQueries\Api\Interfaces;


use App\Models\MeetingChat;

interface MeetingChatQueries
{

    /**
     * @param $data array
     * @return mixed
     */
    public function store($data);

    /**
     * @param $uniqueId integer
     * @return mixed
     */
    public function destroy($uniqueId);

    /**
     * @param $senderId integer
     * @param $recipientId integer
     * @param $chat MeetingChat
     * @return mixed
     */
    public function attachUsers($senderId,$recipientId,$chat);

    /**
     * @param $userId
     * @param $chat MeetingChat
     * @return mixed
     */
    public function detachUser($userId,$chat);

    /**
     * @param $userId
     * @return mixed
     */
    public function getByUser($userId);


    /**
     * @param $uniqueId
     * @return bool|MeetingChat
     */
    public function findByUnique($uniqueId);


    /**
     * @param $id
     * @return bool|MeetingChat
     */
    public function findWithParticipants($id);

    /**
     * @param $chatId
     * @param $participantId
     * @return null|MeetingChat
     */
    public function getByIdAndParticipant($chatId, $participantId);

    /**
     * @param $participantId
     * @return bool
     */
    public function isChatParticipant($participantId);

    /**
     * @param $chatId integer
     * @return mixed
     */
    public function findWith($chatId);


    /**
     * @param $userId integer

     * @param $meetingId integer
     * @return bool
     */
    public function isExist($userId, $meetingId);


    /**
     * @param $chatId
     * @param $whoBlockedId
     * @return mixed
     */
    public function block($chatId, $whoBlockedId);

    /**
     * @param $chatId
     * @return mixed
     */
    public function unblock($chatId);

    public function destroyByMeeting($meetingId);
}

