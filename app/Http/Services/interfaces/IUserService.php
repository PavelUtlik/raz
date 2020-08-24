<?php

namespace App\Http\Services\interfaces;



use App\Models\User;

interface IUserService
{

    /**
     * @param $data array
     * @return bool|array
     */
    public function register($data);

    /**
     * @param $id
     * @return User|null
     */
    public function getUser($id);

    /**
     * @param $id
     * @param $data
     * @return User|false
     */
    public function update($id,$data);

}