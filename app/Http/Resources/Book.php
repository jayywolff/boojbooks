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
            'published_at' => isset($this->published_at) ? $this->published_at->toDateString() : null,
            'summary'      => $this->summary,
            'priority'     => $this->whenPivotLoaded('user_books', function () {
                return $this->pivot->priority;
            }),
            'read'         => $this->whenPivotLoaded('user_books', function () {
                return (bool) $this->pivot->read;
            }),
        ];
    }
}
