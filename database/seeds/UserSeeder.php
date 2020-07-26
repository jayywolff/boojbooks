<?php

use App\User;
use App\Book;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->state('test_account')->create();

        factory(User::class, 5)->create()->each(function ($user) {
            $user->books()->attach(
                factory(Book::class, rand(1, 8))->create()
            );
        });
    }
}
