<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class BookResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "book_name" => $this->book_name,
            "book_price" => $this->book_price,
            "author" => [
                "id" => $this->author->id,
                "author_name" => $this->author->author_name
            ],
            "genres" => GenreCollection::collection($this->genres)
        ];
    }
}
