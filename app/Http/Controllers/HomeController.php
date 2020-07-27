<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (auth()->guest()) {
            return view('welcome');
        }

        $books = auth()->user()->bookCollection();
        return view('books.index', compact('books'));
    }
}
