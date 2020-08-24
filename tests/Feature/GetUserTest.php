<?php

namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetUserTest extends TestCase
{


    public function testNormalData()
    {
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('get', ApiHelper::getFullUrl() . 'user', [
            ]);

        $response->assertStatus(200);
    }
}
