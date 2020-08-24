<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MeetingChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * @var array
     */
    public $data;


    public function __construct($data)
    {
        $this->data = $data;
        $this->dontBroadcastToCurrentUser();
    }


    public function broadcastOn()
    {
        return new PrivateChannel('meeting-chat.' . $this->data['meeting_chat_id']);
    }

    public function broadcastWith()
    {
        return [
            'success' => true,
            'data' => [
                'message' => (string)$this->data['message'],
                'chat_message_type_id' => (int)$this->data['chat_message_type_id'],
                'sender_id' => (int)$this->data['sender_id'],
                'sender_name' => (string)$this->data['sender_name'],
                'time' => (string)$this->data['time'],
            ]
        ];
    }
}




