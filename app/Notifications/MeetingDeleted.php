<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MeetingDeleted extends Notification implements ShouldQueue
{
    use Queueable;


    private $meetingName;

    public function __construct($meetingName)
    {
        $this->meetingName = $meetingName;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }


    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }


//    public function broadcastType()
//    {
//        return 'broadcast.notifications';
//    }

    public function toArray($notifiable)
    {
        return [
            'data' => [
                'code' => 1,
                'type' => 'Встреча  удалена',
                'message' => 'test'
            ]
        ];
    }
}

















