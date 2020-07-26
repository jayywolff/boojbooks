<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];


    /**
     * The author has written many books.
     */
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
