<?php


namespace App\Helpers;


class WebSocketResponseGenerator
{

    const MODEL_NOT_FOUND_ERROR = 1;
    const NOT_ACCESS_RIGHT_ERROR = 2;
    const ANY_ERROR = 2;


    public static function error($errorCode, $msg)
    {
        return [
            'success' => false,
            'error' => $msg,
            'errorCode' => $errorCode ,
        ];
    }

    /**
     * @param $data array
     * @return array
     */
    public static function success($data)
    {
        return [
            'success' => true,
            'data' => $data
        ];
    }

}