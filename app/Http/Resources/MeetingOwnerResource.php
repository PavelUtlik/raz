<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class MeetingOwnerResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'date_of_birth' => $this->date_of_birth,
            'is_vip' => $this->is_vip,
            'photo_url' => $this->photo_url
        ];
    }
}