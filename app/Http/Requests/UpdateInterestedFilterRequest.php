<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class UpdateInterestedFilterRequest extends FormRequest
{



    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'gender_code' => 'int|min:0|max:2',
            'min_age' => 'integer',
            'max_age' => 'integer',
            'max_location_range' => 'integer',
        ];
    }

}