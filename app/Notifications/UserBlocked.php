<?php


namespace App\Notifications;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class UserBlocked extends Notification implements ShouldQueue
{
    use Queueable;


    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }


    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }


    public function toArray($notifiable)
    {
        return [
            'data' => [
                'code' => 2,
                'type' => 'Блокировка пользователя',
                'message' => 'Вас забанили за ненадлежащий контент'
            ]
        ];
    }
}