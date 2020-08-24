<?php

namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use const App\Helpers\DATA;

class UpdateMeetingPhotoTest extends TestCase
{

    public function testNormalData()
    {
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('patch', ApiHelper::getFullUrl() . 'meeting/photo/update', [
                "meeting_id"=>DataForTest::MEETING_ID_FOR_TEST,
                "image"=>DataForTest::IMG

            ]);

        $response->assertStatus(202);
    }

    public function testFailData(){

        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('patch', ApiHelper::getFullUrl() . 'meeting/photo/update', [
                "meeting_id"=>42333333333333333333332,
                "image"=>DataForTest::IMG
            ]);

        $response->assertStatus(422);
    }
}
