<?php


namespace App\Notifications;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class SendFeedback extends Notification implements ShouldQueue
{
    use Queueable;


    private $theme;
    private $description;
    private $addInfo;

    /**
     * See FeedbackCodes class
     * @var int
     */
    private $code;

    public function __construct($theme, $description, $code, $addInfo = null)
    {
        $this->theme = $theme;
        $this->description = $description;
        $this->code = $code;
        $this->addInfo = $addInfo;
    }

    public function via($notifiable)
    {
        return ['database'];
    }


    public function toArray($notifiable)
    {

        $data = ['data' => [
            'theme' => $this->theme,
            'description' => $this->description,
            'code' => $this->code
        ]];

        if ($this->addInfo) {
            $data['data']['addInfo'] = $this->addInfo;
        }

        return $data;
    }
}

