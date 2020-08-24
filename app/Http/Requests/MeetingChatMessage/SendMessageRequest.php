<?php

namespace App\Http\Requests\MeetingChatMessage;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'meeting_chat_id' => 'required|integer',
            'chat_message_type_id' => 'required|integer',
            'message' => 'required|string',
            'meeting_id' => 'required|integer',
        ];
    }

}