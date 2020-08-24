<?php


namespace App\Http\Requests\MeetingChat;


use Illuminate\Foundation\Http\FormRequest;

class AttachUserRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'meeting_chat_id' => 'required|integer',
        ];
    }

}