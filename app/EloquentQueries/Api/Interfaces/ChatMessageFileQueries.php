<?php


namespace App\EloquentQueries\Api\Interfaces;


interface ChatMessageFileQueries
{
    /**
     * @param $senderId integer
     * @param $data array
     * @return mixed
     */
    public function create($senderId,$data);


    /**
     * @param $meetingId
     * @return mixed
     */
    public function getByMeeting($meetingId);


    /**
     * @param $ids integer|array<int>
     * @return mixed
     */
    public function destroy($ids);
}