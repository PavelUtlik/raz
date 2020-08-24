<?php


namespace App\Http\Requests\Meeting;


use Illuminate\Foundation\Http\FormRequest;

class MeetingSearchRequest extends FormRequest
{



    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [

        ];
    }

}