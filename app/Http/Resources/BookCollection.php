<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BookCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            "meta" => ["book_count" => $this->collection->count()],
            "data" => BookResource::collection($this->collection)
        ];
    }
}
