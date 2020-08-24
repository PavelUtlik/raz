<?php

namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use const App\Helpers\DATA;

class UpdateMeetingThemeTest extends TestCase
{

    public function testNormalData()
    {
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('patch', ApiHelper::getFullUrl() . 'meeting/theme/update', [
                "meeting_id"=>DataForTest::MEETING_ID_FOR_TEST,
                "meeting_theme_id"=>1
            ]);

        $response->assertStatus(202);
    }

    public function testFailData(){

        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('patch', ApiHelper::getFullUrl() . 'meeting/theme/update', [
                "meeting_id"=>DataForTest::MEETING_ID_FOR_TEST,
                "meeting_theme_id"=>111111111
            ]);

        $response->assertStatus(202);
    }
}
