<?php

namespace Tests\Unit\Models;

use App\Book;
use App\Author;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    /**
     * Ensure we can build and save a valid factory for users
     *
     * @return void
     */
    public function testFactoryIsValid()
    {
        $author = $this->buildAuthor();

        $this->assertTrue($author->save());
    }

    /**
     * Ensure an author can have many books
     *
     * @return void
     */
    public function testBooksAssociation()
    {
        $author = $this->createAuthor();
        $books = factory(Book::class, 2)->state('without_author')->make();

        $author->books()->createMany($books->toArray());

        $this->assertCount(2, $author->books);
    }

    private function buildAuthor()
    {
        return factory(Author::class)->make();
    }

    private function createAuthor()
    {
        return factory(Author::class)->create();
    }
}
