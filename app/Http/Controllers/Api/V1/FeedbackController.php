<?php


namespace App\Http\Controllers\Api\V1;


use App\EloquentQueries\Api\Interfaces\MeetingQueries;
use App\Helpers\FeedbackCodes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Feedback\FeedbackStoreRequest;
use App\Http\Requests\Feedback\MeetingComplaintRequest;
use App\Models\Notification;
use App\Notifications\MeetingComplaint;
use App\Notifications\SendFeedback;

class FeedbackController extends Controller
{

    private $meetingQueries;

    public function __construct(MeetingQueries $meetingQueries)
    {
        $this->meetingQueries = $meetingQueries;
    }

    public function store(FeedbackStoreRequest $request)
    {
//        $addInfo = null;
//        if ($request->has('addInfo')) {
//            $addInfo = $request->get('addInfo');
//        }
//
//        auth()->user()->notify(
//            new SendFeedback($request->theme_code, $request->description, $addInfo)
//        );
//
//        if ($request->theme_code === FeedbackCodes::MEETING_COMPLAINT && isset($addInfo['meeting_id'])) {
//            $this->meetingQueries->increment($addInfo['meeting_id'], 'complaint_counter');
//        }
//
//
//        return response()->json([
//            'message' => 'Feedback successfully created'
//        ], 201);
    }

    public function meetingComplaint(MeetingComplaintRequest $request)
    {
        $feedbackCode = FeedbackCodes::MEETING_COMPLAINT;
        $meetingId = $request->meeting_id;
        $userId = auth()->id();

        $notification = Notification::where('notifiable_id', $userId)
            ->where('type', 'App\Notifications\MeetingComplaint')
            ->whereJsonContains('data->meeting_id', $request->meeting_id)
            ->count();

        if ($notification) {
            return response()->json([
                'error' => 'Feedback already exist'
            ], 422);
        }

        $this->meetingQueries->increment($meetingId, 'complaint_counter');

        auth()->user()->notify(
            new MeetingComplaint([
                    'theme' => FeedbackCodes::getThemeNames($feedbackCode),
                    'theme_code' => FeedbackCodes::getThemeDescriptions($feedbackCode),
                    'meeting_id' => $meetingId,
                    'sender_id' => $userId
                ]
            )
        );


        return response()->json([
            'message' => 'Feedback successfully created'
        ], 201);
    }


}





















