<?php

namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InterestedFilterTest extends TestCase
{


    public function testNormalData()
    {
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('PUT', ApiHelper::getFullUrl() . 'interested-filter/update', [
            "gender_code" => "2",
            "latitude" => "33",
            "longitude" => "33",
        ]);

        $response->assertStatus(202);
    }

    public function testFailData()
    {
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('PUT',config('app.url') . 'api/' . config('app.actual_api_version') . '/interested-filter/update', [
                "gender_code" => "22",
                "latitude" => "33",
                "longitude" => "33",
            ]);

        $response->assertStatus(422);
    }
}
