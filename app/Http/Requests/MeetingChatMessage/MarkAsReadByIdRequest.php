<?php


namespace App\Http\Requests\MeetingChatMessage;


use Illuminate\Foundation\Http\FormRequest;

class MarkAsReadByIdRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'message_unique_ids' => 'required|array',
        ];
    }

}