<?php

namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use App\Models\Meeting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use const App\Helpers\DATA;

class DeleteMeetingTest extends TestCase
{



    public function testNormalData ()
    {

        $response = $this -> withHeaders ( DataForTest::HEADERS )
            -> json ( 'post' , ApiHelper ::getFullUrl () . 'meeting' , [

                    "latitude" => "34" ,
                    "longitude" => "34" ,
                    "new_meeting_theme" => "test" ,
                    "owner_id" => DataForTest::USER_ID_FOR_TEST ,
                    "end_time" => "2020-06-17 20:20:20" ,
                    "image" => DataForTest::IMG

            ] );

        $response -> assertStatus ( 201 );


        $meeting = Meeting ::where ( 'owner_id' , DataForTest::USER_ID_FOR_TEST ) ->orderBy('id', 'DESC')->first();

        $response = $this -> withHeaders ( DataForTest::HEADERS )
            -> json ( 'delete' , ApiHelper ::getFullUrl () . 'meeting/'. $meeting -> id, [
            ] );

        $response -> assertStatus ( 202 );
    }

    public function testFailData ()
    {
        $response = $this -> withHeaders ( DataForTest::HEADERS )
            -> json ( 'delete' , ApiHelper ::getFullUrl () . 'meeting/232341435234524354' , [
            ] );
        $response -> assertStatus ( 422 );
    }
}
