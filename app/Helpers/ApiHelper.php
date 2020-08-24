<?php


namespace App\Helpers;


class ApiHelper
{


    public static function getFullUrl()
    {
        return config('app.url') . 'api/' . config('app.actual_api_version') . '/';
    }

}