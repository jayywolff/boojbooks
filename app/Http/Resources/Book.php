<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Book extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'author'       => $this->author->name,
            'isbn_10'      => $this->isbn_10,
            'isbn_13'      => $this->isbn_13,
            'published_at' => $this->published_at->toDateString(),
            'summary'      => $this->summary,
            'priority'     => $this->pivot->priority,
            'read'         => (bool) $this->pivot->read
        ];
    }
}