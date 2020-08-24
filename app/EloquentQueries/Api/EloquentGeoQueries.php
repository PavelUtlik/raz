<?php

namespace App\EloquentQueries\Api;


use App\EloquentQueries\Api\Interfaces\GeoQueries;
use App\Exceptions\DBActionException;
use App\Models\Geo;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class EloquentGeoQueries implements GeoQueries
{

    /**
     * @inheritDoc
     */
    public function getCountries($lang)
    {
        try {
            $searchedField = 'country_' . $lang;
            $countries = Geo::select($searchedField)->groupBy($searchedField)->get();
            return $countries->isNotEmpty() ? $countries->pluck($searchedField) : $countries;
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);

            throw new DBActionException('Cannot get countries ', 503);
        }
    }

    public function getCitiesByCountry($lang, $countryName)
    {
        try {
            return Geo::select('city_' . $lang . ' as city', 'latitude', 'longitude')
                ->where('country_' . $lang, 'like', '%' . $countryName . '%')
                ->get();
        } catch (QueryException $queryException) {
            Log::warning('QueryException', [$queryException]);

            throw new DBActionException('Cannot get cities ', 503);
        }
    }
}





















