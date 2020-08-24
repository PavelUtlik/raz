<?php


namespace App\Http\Requests\Meeting;


use Illuminate\Foundation\Http\FormRequest;

class StatusMeetingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
           'meeting_id' => 'required|integer|exists:meetings,id',
        ];
    }
}