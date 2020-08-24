<?php


namespace App\EloquentQueries\Api\Interfaces;


use App\Models\InterestedFilter;
use App\Models\Meeting;

interface MeetingQueries
{

    /**
     * @param $meeting_id
     * @return Meeting
     */
    public function get($meeting_id);


    /**
     * @param $id int
     * @return Meeting
     */
    public function find($id);

    /**
     * @param $data array
     * @return mixed
     */
    public function create($data);

    /**
     * @param $meeting_id int
     * @param $data array
     * @return mixed
     */
    public function update($meeting_id, $data);

    /**
     * @param $meeting_id int
     * @param $data array
     * @return bool
     */
    public function updateTheme($meeting_id, $data);


    /**
     * @param $meeting_id int
     * @param $photo array
     * @return bool
     */
    public function updatePhoto($meeting_id, $photo);

    /**
     * @param $meeting_id int
     * @return bool
     */
    public function destroy($meeting_id);

    /**
     * @param $id
     * @param $relations array
     * @return mixed
     */
    public function findWith($id, $relations);

    /**
     * @param $ownerId integer
     * @return Meeting
     */
    public function findActiveByOwner($ownerId);

    /**
     * @param $ownerId integer
     * @return Meeting
     */
    public function getActiveCountByOwner($ownerId);

    /**
     * @return Meeting[]
     */
    public function getExpired();

    /**
     * @param $id integer
     * @param $statusCode integer
     * @return mixed
     */
    public function changeStatus($id,$statusCode);

    /**
     * @param $id
     * @return mixed
     */
    public function findActive($id);

    /**
     * @param $filter InterestedFilter
     * @param $ownerId int|null
     * @return mixed
     */
    public function search($filter, $ownerId = null);


    public function  countDeletedMessages();

    /**
     * @param $meetingId
     * @param $fieldName string
     * @return mixed
     */
    public function  increment($meetingId, $fieldName);
}

























