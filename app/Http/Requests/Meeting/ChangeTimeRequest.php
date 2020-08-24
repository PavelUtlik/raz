<?php


namespace App\Http\Requests\Meeting;


use Illuminate\Foundation\Http\FormRequest;

class ChangeTimeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'meeting_id' => 'required|integer|exists:meetings,id',
            'time' => 'required|integer|min:0',
        ];
    }
}