<?php

namespace Tests\Unit\Models;

use App\Book;
use App\Author;
use Tests\TestCase;

class BookTest extends TestCase
{
    /**
     * Ensure we can build and save a valid factory for books
     *
     * @return void
     */
    public function testFactoryIsValid()
    {
        $book = $this->buildBook();

        $this->assertTrue($book->save());
    }

    /**
     * Ensure a book has one author
     *
     * @return void
     */
    public function testAuthorAssociation()
    {
        $book = $this->createBook();

        $this->assertTrue($book->author()->exists());
    }

    /**
     * Ensure the published_at attribute is automaticaly casted to a Carbon instance
     *
     * @return void
     */
    public function testPublishedAtIsCastedToCarbonInstance()
    {
        $book = $this->buildBook();

        $this->assertTrue(
            get_class($book->published_at) == 'Illuminate\Support\Carbon'
        );
    }

    private function buildBook()
    {
        return factory(Book::class)->make();
    }

    private function createBook()
    {
        return factory(Book::class)->create();
    }
}
