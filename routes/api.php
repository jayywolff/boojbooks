<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::prefix('v1')->middleware('auth:api')->namespace('Api\V1')->group(function () {
    Route::get('/user', 'UserController@show');

    Route::apiResource('books', 'BookController');
    Route::get('book_search', 'BookSearchController@by_title');
    Route::get('book_search/{id}', 'BookSearchController@by_id');
});
