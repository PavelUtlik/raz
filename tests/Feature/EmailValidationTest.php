<?php

namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class emailValidationTest extends TestCase
{
    const HEADERS = [
        'Content-Type' => 'application/json',
        'Accept' =>'application/json'
    ];
    public function testNormalData()
    {
        $response = $this->withHeaders(self::HEADERS)
            ->json('POST', ApiHelper::getFullUrl() . 'email/and/password/validate', [
               "email"=>'test123@gmail.com'
            ]);

        $response->assertStatus(200);
    }

    public function testFailData(){
        $response = $this->withHeaders(self::HEADERS)
            ->json('POST', ApiHelper::getFullUrl() . 'email/and/password/validate', [
                "email"=>DataForTest::EMAIL_FOR_TEST
            ]);

        $response->assertStatus(422);
    }
}
