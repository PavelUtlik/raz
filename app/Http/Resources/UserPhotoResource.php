<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class UserPhotoResource  extends JsonResource
{

    public function toArray($request)
    {

        return [
            'id'=>$this->id,
            'is_main' => (int)$this->is_main,
            'url' => $this->url,
            'name' => $this->name,
        ];
    }
}