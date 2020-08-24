<?php

namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use App\Helpers\DateHelpers;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckPossibilityCreateMeetingTest extends TestCase
{

    public function testNormalData()
    {
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('POST', ApiHelper::getFullUrl() . 'meeting/create-check', [

            ]);
        $response->assertStatus(200);
    }

    public function testFailData()
    {

        $response = $this->withHeaders(DataForTest::HEADER_AUTHORIZATION_FAIL)
            ->json('POST', ApiHelper::getFullUrl() . 'meeting/time/create-check', [

            ]);

        $response->assertStatus(404);

    }




}
