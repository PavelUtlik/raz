<?php

namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use App\Models\Meeting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MeetingReportingTest extends TestCase
{

    public function testNormalData()
    {
        $meeting = Meeting::create(["latitude"=>"33",
            "longitude"=>"33",
            "new_meeting_theme"=>"pogulyat",
            "owner_id"=>DataForTest::USER_ID_FOR_TEST,
            "image"=>DataForTest::IMG,
            "end_time"=>"2020-06-20 11:11:11"]);

        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('post', ApiHelper::getFullUrl() . 'feedback/meeting-complaint', [
                "meeting_id"=>$meeting->id
            ]);

        $response->assertStatus(201);

        Meeting::where('id',$meeting->id)->delete();
    }

    public function testFailData(){


        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('post', ApiHelper::getFullUrl() . 'feedback/meeting-complaint', [
                "meeting_id"=>234523452345234523452345
            ]);

        $response->assertStatus(422);
    }
}
