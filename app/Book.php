<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'author_id', 'isbn_10', 'isbn_13', 'published_at', 'summary'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'published_at' => 'date'
    ];

    /**
     * The author that belongs to the book.
     */
    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
