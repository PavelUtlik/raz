<?php

namespace App\Http\Requests\Geo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetCountriesRequest extends FormRequest
{



    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'lang' => ['required','string', Rule::in(['ru', 'en'])],
        ];
    }

}