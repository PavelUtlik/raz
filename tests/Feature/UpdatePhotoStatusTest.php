<?php

namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdatePhotoStatusTest extends TestCase
{


    public function testNormalData()
    {

        $response = $this->withHeaders(DataForTest::HEADERS)

            ->json('PUT', ApiHelper::getFullUrl() . 'user/photo/make-main', [
                "photo_id"=>DataForTest::PHOTO_ID_FOR_TEST
            ]);

        $response->assertStatus(201);
    }

    public function testFailData(){

        $response = $this->withHeaders(DataForTest::HEADERS)

            ->json('PUT', ApiHelper::getFullUrl() . 'user/photo/make-main', [
               "photo_id"=>1123123123123123123123
            ]);

        $response->assertStatus(422);
    }
}
