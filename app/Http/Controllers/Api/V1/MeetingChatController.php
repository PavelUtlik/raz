<?php

namespace App\Http\Controllers\Api\V1;

use App\EloquentQueries\Api\Interfaces\MeetingChatQueries;
use App\Http\Controllers\Controller;
use App\Http\Requests\MeetingChat\AttachUserRequest;
use App\Http\Requests\MeetingChat\BlockRequest;
use App\Http\Requests\MeetingChat\SearchMeetingChatRequest;
use App\Http\Requests\MeetingChat\StoreMeetingChatRequest;
use App\Http\Resources\MeetingChatResource;
use App\Http\Services\interfaces\IMeetingChatService;

class MeetingChatController extends Controller
{
    private $meetingChatService;
    private $meetingChatQueries;

    public function __construct(IMeetingChatService $meetingChatService, MeetingChatQueries $meetingChatQueries)
    {
        $this->meetingChatService = $meetingChatService;
        $this->meetingChatQueries = $meetingChatQueries;
    }


    public function store(StoreMeetingChatRequest $request)
    {

        return (new MeetingChatResource(
            $this->meetingChatService->store(
                auth()->id(),
                $request->meeting_id)
            )
        )
        ->response()
        ->setStatusCode(201);
    }

    public function destroy()
    {

    }

    public function getByUser()
    {

        return (new MeetingChatResource($this->meetingChatQueries->getByUser(
            auth()->id()
        )));
    }


    public function block($chatId)
    {
        return $this->meetingChatService->block($chatId, auth()->id());
    }

    public function unblock($chatId)
    {
        return $this->meetingChatService->unblock($chatId, auth()->id());
    }

    public function checkBlock($chatId)
    {
        return $this->meetingChatService->checkBlock($chatId, auth()->id());
    }


}
















