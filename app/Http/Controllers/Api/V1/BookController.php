<?php

namespace App\Http\Controllers\Api\V1;

use App\Book;
use App\Author;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Book as BookResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return auth()->user()->bookCollection();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validator($request->input())->validate();
        $input = $request->only([
            'title', 'isbn_10', 'isbn_13', 'published_at', 'summary'
        ]);

        $input['author_id'] = Author::firstOrCreate(['name' => $request->input('author')])->id;
        if ($request->has('isbn_13')) {
            $book = Book::firstOrCreate(['isbn_13' => $input['isbn_13']], $input);
        } elseif ($request->has('isbn_10')) {
            $book = Book::firstOrCreate(['isbn_10' => $input['isbn_10']], $input);
        }  else {
            $book = Book::create($input);
        }

        if ($request->has('priority')) {
            $user_book = [$book->id => ['priority' => $request->input('priority')]];
        } else {
            $user_book = [$book->id];
        }

        auth()->user()->books()->syncWithoutDetaching($user_book);
        $book = auth()->user()->books()->where('id', $book->id)->with('author')->first();
        return new BookResource($book);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        $book = auth()->user()->books()
                              ->where('id', $book->id)
                              ->with('author')
                              ->first();

        return new BookResource($book);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        try {
            $book = auth()->user()->books()->findOrFail($book->id);
        } catch(ModelNotFoundException $e) {
            return response()->json(['message' => 'Unable to find book'], 400);
        }

        $this->validator($request->input(), false)->validate();

        $input = $request->only([
            'title', 'isbn_10', 'isbn_13', 'published_at', 'summary'
        ]);

        if ($request->has('author') && $request->input('author') != $book->author->name) {
            $input['author_id'] = Author::firstOrCreate(['name' => $request->input('author')])->id;
        }
        if ($request->has('priority')) {
            $book->pivot->priority = $request->input('priority');
        }
        if ($request->has('read')) {
            $book->pivot->read = $request->input('read');
        }

        if ($book->fill($input)->save() && $book->pivot->save()) {
            return new BookResource($book);
        }
        return response()->json(['message' => 'Failed to update book'], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        if (auth()->user()->books()->detach($book->id)) {
            return response()->noContent();
        }
        return response()->json(['message' => 'Unable to delete book'], 400);
    }

    /**
     * Get a validator for an incoming book creation request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, $create = true)
    {
        return Validator::make($data, [
            'title'        => [($create ? 'required' : ''), 'string', 'max:255'],
            'author'       => [($create ? 'required' : ''), 'string', 'max:255'],
            'isbn_10'      => ['digits:10'],
            'isbn_13'      => ['digits:13'],
            'published_at' => ['date', 'before_or_equal:today'],
            'summary'      => ['string'],
            'priority'     => ['integer'],
            'read'         => ['boolean']
        ]);
    }
}
