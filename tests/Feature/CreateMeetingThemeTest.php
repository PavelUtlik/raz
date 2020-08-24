<?php

namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateMeetingThemeTest extends TestCase
{
    public function testNormalData()
    {
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('post', ApiHelper::getFullUrl() . 'meeting/theme/create', [
                "name"=>"edem na rechku Donezk"
            ]);

        $response->assertStatus(201);
    }

    public function testFailData(){


        $response = $this->withHeaders(DataForTest::HEADER_AUTHORIZATION_FAIL)
            ->json('post', ApiHelper::getFullUrl() . 'meeting/theme/create', [
                "name"=>"edem na rechku Donezk"
            ]);

        $response->assertStatus(401);
    }
}
