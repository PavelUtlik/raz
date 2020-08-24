<?php

namespace App\Http\Requests\MeetingChat;

use Illuminate\Foundation\Http\FormRequest;

class StoreMeetingChatRequest  extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'meeting_id' => 'required|integer',
        ];
    }

}