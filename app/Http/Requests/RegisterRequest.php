<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{


    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'gender_code' => 'required|int|min:0|max:1',
            'date_of_birth' => 'required|date_format:"Y-m-d"',
            'interested_gender_code' => 'required|int|min:0|max:2',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'max_location_range' => 'required|int|max:10000',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
            'image' => 'required|string'
        ];
    }

}