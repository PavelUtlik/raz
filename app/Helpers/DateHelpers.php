<?php


namespace App\Helpers;


use Carbon\Carbon;

class DateHelpers
{
    public static function calculationBirthDate($year, $format)
    {
        $date = Carbon::now();
        $date = Carbon::createFromDate($date->year - $year)->format($format);
        return $date;
    }


    public static function getAgeRange($ageFrom, $ageTo)
    {
        $ageFrom = DateHelpers::calculationBirthDate($ageFrom, 'Y-m-d');
        $ageTo = DateHelpers::calculationBirthDate($ageTo + 1, 'Y-m-d'); // добавляем в промежуток тех кто отпраздновал день рождения
        return ['ageFrom' => $ageFrom, 'ageTo' => $ageTo];
    }

    public static function differenceFromNowTime($time)
    {
        $date = Carbon::now();
        return $time ? $date->diffInSeconds($time) : null;
    }

    public static function checkEndEvent($time)
    {
        return Carbon::now() > $time ? true : false;
    }

    public static function convertSecondsToHours($time)
    {
        return $time / 60 / 60;
    }

    public static function convertHourToSeconds($hours)
    {
        return $hours * 3600;
    }

    public static function oneHourInSeconds()
    {
        return 24 * 60 * 60;
    }
}

