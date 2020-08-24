<?php


namespace App\Http\Services\interfaces;


interface IMeetingService
{

    /**
     * @param $user_id int
     * @return mixed
     */
    public function search($user_id);

    /**
     * @param $data array
     * @return mixed
     */
    public function create($data);

    /**
     * @param $data array
     * @return mixed
     */
    public function updateTheme($data);


    /**
     * @param $meetingId
     * @param $photo array
     * @return mixed
     */
    public function updatePhoto($meetingId, $photo);

    /**
     * @param $meetingId
     * @param $userId
     * @param bool $ignoreOwnerCheck
     * @return mixed
     */
    public function destroy($meetingId, $userId, $ignoreOwnerCheck = false);

    /**
     * @param $id
     * @return mixed
     */

    public function checkEndMeeting($id);

    /**
     * @param $id
     * @return mixed
     */

    public function howMuchToTheEndMeeting($id);


    /**
     *
     * @param $meeting_id
     * @param $time
     * @return mixed
     */
    public function updateTime($meeting_id, $time);


}



















