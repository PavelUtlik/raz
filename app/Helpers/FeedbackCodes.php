<?php


namespace App\Helpers;


class FeedbackCodes
{
    const MEETING_COMPLAINT = 1;


    const MEETING_THEMES = [
        1 => 'Жалоба на встречу'
    ];

    const MEETING_DESCRIPTIONS = [
        1 => 'Пользователь пожаловался на встречу'
    ];

    public static function getThemeNames($themeCode)
    {
        return self::MEETING_THEMES[$themeCode];
    }

    public static function getThemeDescriptions($themeCode)
    {
        return self::MEETING_DESCRIPTIONS[$themeCode];
    }

}