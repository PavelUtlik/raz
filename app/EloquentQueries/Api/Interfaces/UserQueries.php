<?php


namespace App\EloquentQueries\Api\Interfaces;


use App\Models\User;
use Illuminate\Support\Collection;

interface UserQueries
{


    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return bool|User
     */
    public function create($data);


    /**
     * @param $id int
     * @return User
     */
    public function find($id);

    /**
     * @param $id int
     * @param $data array
     * @return User|false
     */
    public function update($id, $data);


}




















