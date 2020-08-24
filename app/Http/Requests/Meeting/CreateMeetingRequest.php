<?php

namespace App\Http\Requests\Meeting;

use Illuminate\Foundation\Http\FormRequest;

class CreateMeetingRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'owner_id' => 'required|integer',
            'end_time' => 'required|date_format:"Y-m-d H:i:s"',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'image' => 'required|string',
            'meeting_theme_id' => 'integer|required_without:new_meeting_theme',
            'new_meeting_theme' => 'string|required_without:meeting_theme_id',
        ];
    }

}