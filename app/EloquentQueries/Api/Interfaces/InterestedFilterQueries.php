<?php


namespace App\EloquentQueries\Api\Interfaces;


use App\Models\InterestedFilter;

interface InterestedFilterQueries
{

    /**
     * @param $user_id
     * @param $data
     * @return bool|InterestedFilter
     */
    public function create($user_id, $data);


    /**
     * @param $user_id
     * @param $data
     * @return bool|InterestedFilter
     */
    public function update($user_id, $data);


    /**
     * @param $userId
     * @return InterestedFilter|null
     */
    public function findByUser($userId);

}