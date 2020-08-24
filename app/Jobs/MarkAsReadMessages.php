<?php


namespace App\Jobs;


use App\EloquentQueries\Api\Interfaces\ChatMessageQueries;
use App\Helpers\FileHelper;
use App\Http\Services\interfaces\IMeetingChatService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MarkAsReadMessages  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $senderId;
    protected $meetingChatId;

    public function __construct($senderId,$meetingChatId)
    {
        $this->senderId = $senderId;
        $this->meetingChatId = $meetingChatId;
    }


    public function handle(ChatMessageQueries $chatMessageQueries)
    {

        Log::info('job ' . __CLASS__ . ' started');

        $chatMessageQueries->markAsRead($this->senderId, $this->meetingChatId);

        Log::info('job ' . __CLASS__ . ' end');
    }
}



















