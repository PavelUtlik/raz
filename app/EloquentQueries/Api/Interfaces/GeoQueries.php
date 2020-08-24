<?php


namespace App\EloquentQueries\Api\Interfaces;


use App\Models\Geo;

interface GeoQueries
{

    /**
     * @param $lang
     * @return Geo []
     */
    public function getCountries($lang);

    public function getCitiesByCountry($lang, $countryName);

}