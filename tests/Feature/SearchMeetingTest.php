<?php

namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchMeetingTest extends TestCase
{


    public function testNormalData()
    {
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('get', ApiHelper::getFullUrl() . 'meeting/search', [
            ]);
        $response->assertStatus(200);
    }
}
