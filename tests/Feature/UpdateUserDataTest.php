<?php

namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateUserDataTest extends TestCase
{

    public function testNormalData()
    {
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('PUT', ApiHelper::getFullUrl() . 'user/update', [
                "name" => "Pavel". date('h:i'),
            ]);

        $response->assertStatus(202);
    }

    public function testNotExistDateOfBirth()
    {
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('PUT', ApiHelper::getFullUrl() . 'user/update', [
               "date_of_birth" => '20.04.1998',
            ]);

        $response->assertStatus(422);
    }

}
