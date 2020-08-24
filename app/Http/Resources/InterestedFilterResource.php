<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class InterestedFilterResource  extends JsonResource
{

    public function toArray($request)
    {
        return [
            'max_location_range' => (int)$this->max_location_range,
            'min_age' => (int)$this->min_age,
            'max_age' => (int)$this->max_age,
            'gender_code' => (int)$this->gender_code,
            'latitude' => (float)$this->latitude,
            'longitude' => (float)$this->longitude,
        ];
    }
}
