<?php


namespace App\Http\Services\interfaces;


use App\Models\MeetingChat;

interface IMeetingChatService
{


    /**
     * @param $senderId integer
     * @param $meetingId
     * @return mixed
     */
    public function store($senderId,$meetingId);


    /**
     * @param $chatId integer
     * @return bool
     */
    public function destroy($chatId);


    /**
     * @param $senderId integer
     * @param $data array
     * @return mixed
     */
    public function sendMessage($senderId,$data);

    /**
     * @param $userId integer
     * @param $chatId int
     * @return mixed
     */
    public function getMessages($userId,$chatId);

    /**
     * @param $chatId
     * @param $whoBlockedId
     * @return mixed
     */
    public function block($chatId, $whoBlockedId);

    /**
     * @param $chatId
     * @param $whoUnblockedId
     * @return mixed
     */
    public function unBlock($chatId, $whoUnblockedId);

    /**
     * @param $chatId int
     * @param $whoCheckedId int
     * @return mixed
     */
    public function checkBlock($chatId, $whoCheckedId);





}