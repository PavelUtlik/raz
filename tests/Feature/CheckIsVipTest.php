<?php

namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckIsVipTest extends TestCase
{

    public function testNormalData()
    {
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('post', ApiHelper::getFullUrl() . 'user/check-vip', [
            ]);

        $response->assertStatus(200);
    }

    public function testFailData(){


        $response = $this->withHeaders(DataForTest::HEADER_AUTHORIZATION_FAIL)
            ->json('post', ApiHelper::getFullUrl() . 'user/check-vip', [
            ]);

        $response->assertStatus(401);
    }
}
