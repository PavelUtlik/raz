<?php


namespace App\EloquentQueries\Api\Interfaces;


use App\Models\MeetingTheme;

interface MeetingThemeQueries
{

    /**
     * @param $data array
     * @return MeetingTheme
     */
    public function create($data);

    /**
     * @param $id int
     * @param $data array
     * @return MeetingTheme
     */
    public function update($id, $data);

    /**
     * @param $id int
     * @return bool
     */
    public function destroy($id);

    /**
     * @param $id
     * @return MeetingTheme
     */

    public function get($id);

}