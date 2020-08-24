<?php


namespace App\Http\Requests\MeetingChat;


use Illuminate\Foundation\Http\FormRequest;

class BlockRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'chat_id' => 'required|integer',
        ];
    }

}