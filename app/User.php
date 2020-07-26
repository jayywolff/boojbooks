<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_verified_at', 'api_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The books that belong to the user.
     */
    public function books()
    {
        return $this->belongsToMany(Book::class, 'user_books')
                    ->withPivot('priority', 'read')
                    ->withTimestamps();
    }

    /**
     * Generate a unique api token for users
     *
     * @return string
     */
    public static function generateApiToken()
    {
        $token = Str::random(60);
        while (User::where('api_token', $token)->exists()) {
            $token = Str::random(60);
        }

        return $token;
    }
}
