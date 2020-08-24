<?php

namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckMeetingEndTest extends TestCase
{


    public function testNormalData()
    {
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('post', ApiHelper::getFullUrl() . 'meeting/time/check-end', [
                "meeting_id" => DataForTest::MEETING_ID_FOR_TEST,
            ]);

        $response->assertStatus(200);

    }

    public function testFailData()
    {
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('post', ApiHelper::getFullUrl() . 'meeting/time/check-end', [
                "meeting_id" => "123wd",
            ]);
        $response->assertStatus(422);
    }

}
