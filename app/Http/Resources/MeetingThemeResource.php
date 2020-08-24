<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class MeetingThemeResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user_id' => (int)$this->user_id,
        ];
    }
}