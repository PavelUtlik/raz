<?php


namespace App\Http\Controllers\Api\V1;


use App\EloquentQueries\Api\Interfaces\ChatMessageQueries;
use App\Events\MeetingChatMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\MeetingChatMessage\GetMessagesRequest;
use App\Http\Requests\MeetingChatMessage\MarkAsReadByIdRequest;
use App\Http\Requests\MeetingChatMessage\SendMessageRequest;
use App\Http\Resources\ChatMessagePaginatedResource;
use App\Http\Resources\UnreadMessageResource;
use App\Http\Services\interfaces\IMeetingChatService;
use App\Models\Meeting;

class MeetingChatMessageController extends Controller
{

    private $meetingChatService;
    private $chatMessageQueries;

    public function __construct(IMeetingChatService $meetingChatService, ChatMessageQueries $chatMessageQueries)
    {
        $this->meetingChatService = $meetingChatService;
        $this->chatMessageQueries = $chatMessageQueries;
    }

    public function sendMessage(SendMessageRequest $request)
    {
        return $this->meetingChatService->sendMessage(auth()->id(), $request->all());
    }

    public function getMessages($chatId)
    {
        return (new ChatMessagePaginatedResource($this->meetingChatService->getMessages(auth()->id(), $chatId)))
            ->response()
            ->setStatusCode(200);
    }

    public function getUnread()
    {
        return (new UnreadMessageResource($this->chatMessageQueries->getUnreadMessages(auth()->id())))
            ->response()
            ->setStatusCode(200);
    }

    public function markAsReadByUniqueId(MarkAsReadByIdRequest $request)
    {

        $this->chatMessageQueries->markAsReadByUniqueId($request->message_unique_ids);
        return response()->json([
            'message' => 'successfully marked'
        ], 202);
    }

}























