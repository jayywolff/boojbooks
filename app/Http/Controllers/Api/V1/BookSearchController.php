<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\GoogleBooksApiService as GoogleBooksApi;

class BookSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function by_title(Request $request)
    {
        $title = $request->input('title');

        if (is_null($title) || $title == '') {
            return response()->json(['message' => 'Missing title attribute'], 400);
        }
        return GoogleBooksApi::searchByTitle($request->input('title'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function by_id(Request $request)
    {
        $book = GoogleBooksApi::searchByVolumeId($request->id);

        if (is_null($book)) {
            return response()->json(['message' => 'Book not found'], 400);
        }

        return $book;
    }
}
