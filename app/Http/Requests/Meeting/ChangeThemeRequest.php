<?php


namespace App\Http\Requests\Meeting;


use Illuminate\Foundation\Http\FormRequest;

class ChangeThemeRequest  extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'meeting_id' => 'integer|required',
            'meeting_theme_id' => 'integer|required_without:new_meeting_theme',
            'new_meeting_theme' => 'string|required_without:meeting_theme_id',
        ];
    }

}