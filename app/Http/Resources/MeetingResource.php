<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class MeetingResource extends JsonResource
{

    public function toArray($request)
    {

        $data = [
            'id' => $this->id,
            'end_time' => $this->end_time,
            'latitude' => (float)$this->latitude,
            'longitude' => (float)$this->longitude,
            'owner' => MeetingOwnerResource::make($this->owner),
            'theme' => MeetingThemeResource::make($this->theme),
            'photo' => MeetingPhotoResource::make($this->photo),
            'status' => MeetingStatusResource::make($this->status),
        ];

        if (isset($this->chats)){
            $data['chats']  = $this->chats;
        }


        return $data;
    }
}