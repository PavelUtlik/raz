<?php


namespace App\Helpers;

class DistanceHelpers

{
    const EARTH_RADIUS = 6372795;

    public static function calculateDistance($fA, $lA, $fB, $lB){

        // Радиус земли
        /*
        * Расстояние между двумя точками
        * $φA, $λA - широта, долгота 1-й точки,
        * $φB, $λB - широта, долгота 2-й точки
        * Написано по мотивам http://gis-lab.info/qa/great-circles.html
        * Михаил Кобзарев <mikhail@kobzarev.com>
        *
        */

// перевести координаты в радианы
            $lat1 = $fA * M_PI / 180;
            $lat2 = $fB * M_PI / 180;
            $long1 = $lA * M_PI / 180;
            $long2 = $lB * M_PI / 180;

// косинусы и синусы широт и разницы долгот
            $cl1 = cos($lat1);
            $cl2 = cos($lat2);
            $sl1 = sin($lat1);
            $sl2 = sin($lat2);
            $delta = $long2 - $long1;
            $cdelta = cos($delta);
            $sdelta = sin($delta);

// вычисления длины большого круга
            $y = sqrt(pow($cl2 * $sdelta, 2) + pow($cl1 * $sl2 - $sl1 * $cl2 * $cdelta, 2));
            $x = $sl1 * $sl2 + $cl1 * $cl2 * $cdelta;

//
            $ad = atan2($y, $x);
            $dist = $ad * self::EARTH_RADIUS;

            return $dist;
        }


}