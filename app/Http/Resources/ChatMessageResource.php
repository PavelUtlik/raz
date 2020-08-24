<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ChatMessageResource extends JsonResource
{

    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'message' => $this->message,
            'chat_message_type_id' => (int)$this->chat_message_type_id,
            'created_at' => $this->created_at,
            'user' => UserResource::make($this->user),

        ];
    }
}