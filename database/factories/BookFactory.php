<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Book;
use App\Author;
use Faker\Generator as Faker;

$book = $factory->define(Book::class, function (Faker $faker) {
    return [
        'title'        => Str::title($faker->catchPhrase),
        'author_id'    => factory(Author::class),
        'isbn_10'      => $faker->isbn10,
        'isbn_13'      => $faker->isbn13,
        'published_at' => $faker->date,
        'summary'      => $faker->paragraph
    ];
});


$factory->state(Book::class, 'without_author', ['author_id' => null]);
