<?php

namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    const HEADERS = [
        'Content-Type'=>'application/json',
        'Accept' => 'application/json',
    ];

    public function testNormalData()
    {
        $response = $this->withHeaders(self::HEADERS)
            ->json('post', ApiHelper::getFullUrl() . 'login', [
                "email"=>DataForTest::EMAIL_FOR_TEST,
                "password"=>DataForTest::PASSWORD_FOR_EMAIL

            ]);
        $token = $response->getData()->access_token;

        $header = [
            'Accept' => 'application/json',
            'Authorization' =>'Bearer '.$token
        ];


        $response = $this->withHeaders($header)
            ->json('get', ApiHelper::getFullUrl() . 'logout', [
            ]);

        $response->assertStatus(200);
    }
}
