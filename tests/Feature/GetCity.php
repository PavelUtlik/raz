<?php

namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class getCity extends TestCase
{

    public function testNormalData()
    {
        $response = $this->withHeaders(DataForTest::HEADERS)

            ->json('get', ApiHelper::getFullUrl() . 'geo/cities', [
                "lang"=>"ru",
                "country"=>"Беларусь"
            ]);

        $response->assertStatus(200);
    }
}
