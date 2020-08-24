<?php

namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetUserPhotoTest extends TestCase
{

    public function testNormalData()
    {
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('get', ApiHelper::getFullUrl() . 'user/1/photos/get', [
            ]);

        $response->assertStatus(200);
    }

    public function testFailData(){

        $response = $this->withHeaders(DataForTest::HEADER_AUTHORIZATION_FAIL)
            ->json('get', ApiHelper::getFullUrl() . 'user/12342345243523452345234523452345/photos/get', [
            ]);

        $response->assertStatus(401);
    }

}
