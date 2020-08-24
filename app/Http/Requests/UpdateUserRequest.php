<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{



    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'string',
            'gender_code' => 'int|min:0|max:1',
            'date_of_birth' => 'date_format:"d-m-Y"',
            'interested_gender_code' => 'int|min:0|max:2',
            'email' =>['email', 'max:255', 'unique:users,email,' . auth()->user()->id],
            'image' => 'string'
        ];
    }

}