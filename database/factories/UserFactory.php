<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Book;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name'              => $faker->name,
        'email'             => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'remember_token'    => Str::random(10),
        'api_token'         => User::generateApiToken()
    ];
});

//A test user with static credentials for testing
$factory->state(User::class, 'test_account', [
    'name'      => 'John Doe',
    'email'     => 'test@test.com',
    'password'  => Hash::make('Test1234'),
    'api_token' => '7ulARQG7LcmpZBe22TR878QbKq1rJ9KxWUjoMcQ5uJfYf5y53Cam33wTuODE'

]);

$factory->afterCreatingState(User::class, 'test_account', function ($user) {
    $user->books()->attach(factory(Book::class, 5)->create());
});
