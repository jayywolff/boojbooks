<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\GoogleBooksApiService as GoogleBooksApi;

class Book extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'author_id', 'isbn_10', 'isbn_13', 'published_at', 'summary', 'volume_id'
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

    public function buildWithGoogle()
    {
        if (is_null($this->volume_id)) {
            return null;
        }

        $data = GoogleBooksApi::searchByVolumeId($this->volume_id);
        if (is_null($data)) {
            return false;
        }
        $this->author()->associate(Author::firstOrCreate(['name' => $data['author']]));
        $this->title        = $data['title'];
        $this->isbn_10      = $data['isbn_10'];
        $this->isbn_13      = $data['isbn_13'];
        $this->published_at = $data['published_at'];
        $this->summary      = $data['description'];
        return $this->save();
    }
}
