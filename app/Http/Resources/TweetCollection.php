<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TweetCollection extends ResourceCollection
{
    public $collects = TweetResource::class;
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'total' => $this->resource->total(),
            'lastPage' => $this->resource->lastPage(),
            'perPage' => $this->resource->perPage(),
            'currentPage' => $this->resource->currentPage(),
            'nextPageUrl' => $this->resource->nextPageUrl(),

        ];
    }
}

