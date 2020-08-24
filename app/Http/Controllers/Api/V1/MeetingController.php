<?php

namespace App\Http\Controllers\Api\V1;

use App\EloquentQueries\Api\EloquentMeetingQueries;
use App\EloquentQueries\Api\EloquentMeetingThemeQueries;
use App\Http\Controllers\Controller;
use App\Http\Requests\Meeting\ChangePhotoRequest;
use App\Http\Requests\Meeting\ChangeThemeRequest;
use App\Http\Requests\Meeting\ChangeTimeRequest;
use App\Http\Requests\Meeting\CheckCreateRequest;
use App\Http\Requests\Meeting\CreateMeetingRequest;
use App\Http\Requests\Meeting\CreateThemeRequest;
use App\Http\Requests\Meeting\LeaveMeetingRequest;
use App\Http\Requests\Meeting\MeetingDeleteRequest;
use App\Http\Requests\Meeting\MeetingSearchRequest;
use App\Http\Requests\Meeting\ParticipateMeetingRequest;
use App\Http\Requests\Meeting\StatusMeetingRequest;
use App\Http\Resources\MeetingPaginatedResource;
use App\Http\Resources\MeetingResource;
use App\Http\Resources\MeetingThemeResource;
use App\Http\Resources\UserPhotoResource;
use App\Http\Resources\UserResource;
use App\Http\Services\MeetingService;
use App\Models\Meeting;
use App\Models\MeetingPhoto;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    private $meetingService;
    private $meetingQueries;
    private $meetingThemeQueries;

    public function __construct(MeetingService $meetingService, EloquentMeetingQueries $meetingQueries, EloquentMeetingThemeQueries $meetingThemeQueries)
    {
        $this->meetingService = $meetingService;
        $this->meetingQueries = $meetingQueries;
        $this->meetingThemeQueries = $meetingThemeQueries;

    }

    public function search(MeetingSearchRequest $request)
    {
        return (new MeetingPaginatedResource(
            $this->meetingService->search(auth()->id())
        ))->response()
            ->setStatusCode(200);
    }

    public function store(CreateMeetingRequest $request)
    {
        return $this->meetingService->create($request->all());
    }


    public function updateTheme(ChangeThemeRequest $request)
    {
        return $this->meetingService->updateTheme($request->all());
    }

    public function updatePhoto(ChangePhotoRequest $request)
    {
        return $this->meetingService->updatePhoto(
            $request->get('meeting_id'),
            $request->get('image')
        );
    }


    public function destroy($id)
    {
        return $this->meetingService->destroy($id, auth()->id());
    }

    public function checkEndTime(StatusMeetingRequest $request)
    {
        return $this->meetingService->checkEndMeeting($request->get('meeting_id'));
    }

    public function timeToEnd(StatusMeetingRequest $request)
    {
        $timeToEndInSeconds = $this->meetingService->howMuchToTheEndMeeting($request->get('meeting_id'));
        return response()->json([
            'time' => $timeToEndInSeconds
        ], 202);
    }

    public function updateTime(ChangeTimeRequest $request)
    {
        return $this->meetingService->updateTime($request->get('meeting_id'), $request->get('time'));
    }

    public function checkPossibilityCreateMeeting(CheckCreateRequest $checkCreateRequest)
    {
        return $this->meetingService->checkPossibilityCreateMeeting(auth()->id());
    }

    public function getTheme()
    {


        return MeetingThemeResource::collection($this->meetingThemeQueries->get(auth()->id()))
            ->response()
            ->setStatusCode(200);

    }

    public function createTheme(CreateThemeRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = auth()->id();
        return (new MeetingThemeResource(
            $this->meetingThemeQueries->create($data)
        ))->response()
            ->setStatusCode(201);
    }

    public function findActiveByOwner()
    {

        $meeting = $this->meetingQueries->findActiveByOwner(auth()->id());


        return $meeting
            ?  (new MeetingResource($meeting ))->response()->setStatusCode(200)
            : response()->json(['data' => null],200)
        ;

    }


}


