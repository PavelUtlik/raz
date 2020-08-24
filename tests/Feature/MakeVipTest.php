<?php

namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MakeVipTest extends TestCase
{

    public function testNormalData()
    {
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('patch', ApiHelper::getFullUrl() . 'user/mark-as-vip', [
            ]);

        $response->assertStatus(202);
    }

    public function testFailData(){


        $response = $this->withHeaders(DataForTest::HEADER_AUTHORIZATION_FAIL)
            ->json('patch', ApiHelper::getFullUrl() . 'user/mark-as-vip', [
            ]);

        $response->assertStatus(401);
    }
}
