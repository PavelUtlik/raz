<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class ChatMessagePaginatedResource extends ResourceCollection
{

    public function toArray($request)
    {

        $response = [
            'total' => $this->total(),
            'count' => $this->count(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'total_pages' => $this->lastPage(),
            'prev_page_url' => $this->previousPageUrl(),
            'next_page_url' => $this->nextPageUrl(),
        ];

        $response['data'] = ChatMessageResource::collection($this->collection);

        return $response;
    }
}