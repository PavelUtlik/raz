<?php


namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use App\Models\Meeting;
use App\Models\UserPhoto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DeletePhotoTest extends TestCase
{

    public function testNormalData()
    {

        $data = [
            'image' => DataForTest::IMG,
            'user_id' => DataForTest::USER_ID_FOR_TEST,
            'is_main' => 0];

        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('post',config('app.url') . 'api/' . config('app.actual_api_version') . '/user/photo/add', $data);

        $response->assertStatus(201);

        $photo = UserPhoto::where('user_id', $data['user_id'])->first();

        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('delete',config('app.url') . 'api/' . config('app.actual_api_version') . '/user/photo/delete', [
                'photo_id' => $photo->id
            ]);
        $response->assertStatus(202);


    }
    public function testFailData()
    {
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('delete',ApiHelper::getFullUrl() . 'user/photo/delete', [
                'photo_id' => 12341234123412341234
            ]);
        $response->assertStatus(422);
    }
}