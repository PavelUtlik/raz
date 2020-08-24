<?php

namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use App\Models\Meeting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateMeetingTimeTest extends TestCase
{



    public function testNormalData()
    {
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('patch', ApiHelper::getFullUrl() . 'meeting/time/update', [
                "meeting_id" => DataForTest::MEETING_ID_FOR_TEST,
                "time" =>30,
            ]);
        Meeting::where('id',DataForTest::MEETING_ID_FOR_TEST)->update(['time_extension_counter'=>0]);
        $response->assertStatus(202);
    }

    public function testFailData(){
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('patch', ApiHelper::getFullUrl() . 'meeting/time/update', [
                "meeting_id" => 442,
                "time" =>30,
            ]);
        $response->assertStatus(422);
    }


}
