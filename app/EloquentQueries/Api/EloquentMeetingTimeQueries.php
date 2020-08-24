<?php


namespace App\EloquentQueries\Api;


use App\EloquentQueries\Api\Interfaces\MeetingQueries;
use App\EloquentQueries\Api\Interfaces\MeetingTimeQueries;
use App\Helpers\DateHelpers;
use App\Models\Meeting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EloquentMeetingTimeQueries
{

    public function checkEndMeeting($id)
    {
        $meeting = Meeting::where('id', $id)->first();
        DateHelpers::checkEndEvent($meeting['end_time']);
    }

    public function checkEndTimeMeeting($id)
    {
        $meeting = Meeting::where('id', $id)->first();
        if (DateHelpers::checkEndEvent($meeting['end_time']) === false) {
            return DateHelpers::differenceFromNowTime($meeting['end_time']);
        }
    } // perenesti v servise




    public function addTimeToMeeting($id, $minutes)
    {
        Meeting::where('id', $id)->update(['end_time' => DB::raw("DATE_ADD(end_time, INTERVAL $minutes WEEK)")]);
        return true;
    } //ostanetsta v queries

}