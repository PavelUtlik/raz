<?php

namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class getCountries extends TestCase
{
    const HEADERS = [
        'Content-Type' => 'application/json',
        'Accept' =>'application/json'
    ];


    public function testNormalData()
    {
        $response = $this->withHeaders(DataForTest::HEADERS)

            ->json('get', ApiHelper::getFullUrl() . 'geo/countries', [
                "lang"=>"ru"
            ]);

        $response->assertStatus(200);
    }
}
