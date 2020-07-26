<?php

namespace Tests\Unit\Models;

use \App\User;
use \App\Book;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Ensure we can build and save a valid factory for users
     *
     * @return void
     */
    public function testFactoryIsValid()
    {
        $user = $this->buildUser();

        $this->assertTrue($user->save());
    }

    /**
     * Ensure a user can have many books
     *
     * @return void
     */
    public function testBooksAssociation()
    {
        $user = $this->createUser();

        $user->books()->attach(factory(Book::class, 2)->create());

        $this->assertTrue($user->books()->count() == 2);
    }

    /**
     * Ensure api token is always unique
     * If this test becomes flaky its broken..
     *
     * @return void
     */
    public function testGenerateApiToken()
    {
        $user = $this->createUser();

        $this->assertTrue($user->api_token != User::generateApiToken());
    }

    private function buildUser()
    {
        return factory(User::class)->make();
    }

    private function createUser()
    {
        return factory(User::class)->create();
    }
}
