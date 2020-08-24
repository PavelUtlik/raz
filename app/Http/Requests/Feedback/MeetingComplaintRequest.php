<?php


namespace App\Http\Requests\Feedback;


use Illuminate\Foundation\Http\FormRequest;

class MeetingComplaintRequest extends FormRequest
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