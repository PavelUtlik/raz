<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{


    public function toArray($request)
    {

        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'gender_code' => (int)$this->gender_code,
            'date_of_birth' => $this->date_of_birth,
            'is_vip' => (int)$this->is_vip,
        ];

        if (isset($this->getRelations()['photo'])) {

            $data['photo'] = UserPhotoResource::collection($this->photo);
        }

        if (isset($this->getRelations()['gender'])) {

            $data['gender'] = GenderResource::make($this->gender);
        }

        if (isset($this->getRelations()['interestedFilter'])) {

            $data['interested_filter'] = InterestedFilterResource::make($this->interestedFilter);
        }

        return $data;
    }
}
