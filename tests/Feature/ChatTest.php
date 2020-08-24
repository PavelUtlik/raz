<?php

namespace Tests\Feature;

use App\Helpers\ApiHelper;
use App\Helpers\DataForTest;
use App\Models\Meeting;
use App\Models\MeetingChat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChatTest extends TestCase
{

    public function testNormalData()
    {

        $meeting = $this->createMeting ();

        $chat = $this->createChat ($meeting->id);

        $this->sendMessage ($chat->data->id,$meeting->id);

        $this->getChatUser ();

        $this->getMessageList ($chat->data->id);

        $this->checkBlockChat ($chat->data->id);

        $this->getUnreadMessage ();

        $this->markAsRead($meeting->id);

        $this->blockChat($chat->data->id);

        $this->unblockChat ($chat->data->id);

        Meeting::whereId($meeting->id)->delete();



    }
    private function createMeting(){
        $this->withHeaders(DataForTest::HEADERS)
            ->json('post', ApiHelper::getFullUrl() . 'meeting', [
                "latitude"=>"33",
                "longitude"=>"33",
                "new_meeting_theme"=>"pogulyat",
                "owner_id"=>DataForTest::USER_ID_FOR_TEST,
                "image"=>DataForTest::IMG,
                "end_time"=>"2020-06-20 11:11:11"
            ]);
        return Meeting::where('owner_id',DataForTest::USER_ID_FOR_TEST)->OrderBy('id','desc')->first();
    }

    private function createChat($meetingId){
        $header = DataForTest::HEADERS;
        $header['Authorization'] = DataForTest::AUTHORIZATION_KEY_FOR_MESSAGE_TEST;
        $response = $this->withHeaders($header)
            ->json('post', ApiHelper::getFullUrl() . 'meeting/chat', [
                'meeting_id'=>$meetingId
            ]);
        $chat = $response->getData();
        $response->assertStatus(201);

        return $chat;
    }


    private function sendMessage($chatId,$meetingId){
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('post', ApiHelper::getFullUrl() . 'meeting/chat/message', [
                "meeting_chat_id"=>$chatId,
                "chat_message_type_id"=>1,
                "message"=>"privet",
                "meeting_id"=>$meetingId
            ]);
        $response->assertStatus(201);
    }

     private function getChatUser (){
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('get', ApiHelper::getFullUrl() . 'meeting/chat/user', [
            ]);
        $response->assertStatus(200);
    }

    private function getMessageList($id){
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('get', ApiHelper::getFullUrl() . 'meeting/chat/'.$id.'/message', [
            ]);
        $response->assertStatus(200);
    }

    private function checkBlockChat($id){
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('get', ApiHelper::getFullUrl() . 'meeting/chat/'.$id.'/check-block', [
            ]);
        $response->assertStatus(200);

    }

    private function getUnreadMessage(){
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('get', ApiHelper::getFullUrl() . 'meeting/chat/message/unread', [
            ]);
        $response->assertStatus(200);
    }

    private function markAsRead($meetingId){
        $meetingChat = MeetingChat::where('meeting_id',$meetingId)->first();

        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('post', ApiHelper::getFullUrl() . 'meeting/chat/message/mark-as-read', [
                "message_unique_ids"=>["$meetingChat->unique_id"]
            ]);
        $response->assertStatus(202);
    }

    private function blockChat($id){
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('patch', ApiHelper::getFullUrl() . 'meeting/chat/'.$id.'/block', [
            ]);
        $response->assertStatus(202);
    }

    private function unblockChat($id){
        $response = $this->withHeaders(DataForTest::HEADERS)
            ->json('patch', ApiHelper::getFullUrl() . 'meeting/chat/'.$id.'/unblock', [
            ]);

        $response->assertStatus(202);
    }
}
