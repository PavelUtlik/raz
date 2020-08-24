<?php

namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use App\Models\User;
use App\Models\UserPhoto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AddUserPhotoTest extends TestCase
{
    public function testNormalData()
    {

        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('post', ApiHelper::getFullUrl() . 'user/photo/add', [
                'image' => DataForTest::IMG,
                'user_id' => DataForTest::USER_ID_FOR_TEST
            ]);
        $response->assertStatus(201);

        $deletePath = config('public/files/images/user_photos');
        $userPhoto = UserPhoto::where('user_id', DataForTest::USER_ID_FOR_TEST)->first();
        Storage::delete($deletePath.'/'.$userPhoto['name']);
        UserPhoto::where('user_id', DataForTest::USER_ID_FOR_TEST)->delete();
    }

    /**
     * @return void
     */
    public function testFailData()
    {
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('post', ApiHelper::getFullUrl() . 'user/photo/add', [
                'image' => DataForTest::IMG,
                'user_id' => 100500000,
            ]);
        $response->assertStatus(422);
    }
}
