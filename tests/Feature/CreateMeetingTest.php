<?php

namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateMeetingTest extends TestCase
{

    public function testNormalData()
    {
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('post', ApiHelper::getFullUrl() . 'meeting', [
                "latitude"=>"33",
                "longitude"=>"33",
                "new_meeting_theme"=>"pogulyat",
                "owner_id"=>DataForTest::USER_ID_FOR_TEST,
                "image"=>DataForTest::IMG,
                "end_time"=>"2020-06-20 11:11:11"
            ]);

        $response->assertStatus(201);
    }

    public function testFailData()
    {
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('post', ApiHelper::getFullUrl() . 'meeting', [
                "latitude"=>"33",
                "longitude"=>"33",
                "new_meeting_theme"=>"pogulyat",
                "owner_id"=>34534534534536,
                "image"=>DataForTest::IMG,
                "end_time"=>"23455-06-20 11-11-11"
            ]);

        $response->assertStatus(422);
    }
}
