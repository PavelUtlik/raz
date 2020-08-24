<?php


namespace App\Http\Controllers\Api\V1;


use App\EloquentQueries\Api\Interfaces\GeoQueries;
use App\Http\Controllers\Controller;
use App\Http\Requests\Geo\GetCitiesRequest;
use App\Http\Requests\Geo\GetCountriesRequest;
use App\Http\Resources\GeoResource;

class GeoController extends Controller
{

    private $geoQueries;

    public function __construct(GeoQueries $geoQueries)
    {
        $this->geoQueries = $geoQueries;
    }


    public function getCountries(GetCountriesRequest $request)
    {
        return (new GeoResource(
            $this->geoQueries->getCountries($request->lang)
        ))->response()
            ->setStatusCode(200);
    }

    public function getCities(GetCitiesRequest $request)
    {
        return (new GeoResource(
            $this->geoQueries->getCitiesByCountry($request->lang, $request->country)
        ))->response()
            ->setStatusCode(200);
    }

}