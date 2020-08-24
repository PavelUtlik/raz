<?php

namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PasswordResetCreateTest extends TestCase
{
    const HEADERS = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json'
    ];

    public function testNormalData()
    {
        $response = $this->withHeaders(self::HEADERS)
            ->json('POST', ApiHelper::getFullUrl() . 'password/reset/create', [
                "email"=>DataForTest::EMAIL_FOR_TEST
            ]);

        $response->assertStatus(200);
    }

    public function testFailData(){

        $response = $this->withHeaders(self::HEADERS)
            ->json('POST', ApiHelper::getFullUrl() . 'password/reset/create', [
                "email"=>"pavel.utlikkkkk@gmail.com"
            ]);
        $response->assertStatus(422);
    }

}
