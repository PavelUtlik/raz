<?php


namespace App\Helpers;


const DATA = [
    NotificationThemeCode::RESET_REQUEST => ['message' => '123123123', 'button_message' => NotificationButtonMessage::OK],
    NotificationThemeCode::RESET_SUCCESS => ['message' => 'privet $data mir', 'button_message' => NotificationButtonMessage::REPORT],
];


class NotificationHelpers
{
    public static function getNotification($theme, $data = [])
    {
        return ['theme' => $theme, 'data' => self::getData($theme, $data)];
    }

    public static function getData($theme, $data)
    {
        return ['message' => self::getMessage($theme, $data), 'button_message' => self::getButtonMessage($theme)];
    }

    public static function getMessage($theme, $data)
    {
        if (!empty($data)) {
            $messageParts = explode('$data', DATA[$theme]['message']);
            return $messageParts[0] . $data . $messageParts[1];
        }

        return DATA[$theme]['message'];
    }

    public static function getButtonMessage($theme)
    {
        return DATA[$theme]['button_message'];
    }
}

[
    'theme' => '123123',
    'data'=>
        [
        'message'=>'123123',
        'button_message'=>'123123',
    ],
    'status'=>'block,delete,',
    //''=>'',
];