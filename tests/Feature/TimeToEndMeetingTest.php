<?php

namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use const App\Helpers\DATA;

class TimeToEndMeetingTest extends TestCase
{


    public function testNormalData()
    {
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('POST', ApiHelper::getFullUrl() . 'meeting/time/check-end', [
                "meeting_id" =>DataForTest::MEETING_ID_FOR_TEST,
            ]);
        $response->assertStatus(200);
    }

    public function testNotExistMeeting()
    {
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('POST', ApiHelper::getFullUrl() . 'meeting/time/check-end', [
                "meeting_id" => 123412341234,
            ]);
        $response->assertStatus(422);
    }
}
