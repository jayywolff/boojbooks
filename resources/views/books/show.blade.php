@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row my-4">
        <div class="col-7">
            <h2>Book Info</h2>
        </div>
        <div class="col-5 text-right">
            <a href="{{ url('/') }}" class="btn btn-warning">
                Back
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-return-left" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M5.854 5.646a.5.5 0 0 1 0 .708L3.207 9l2.647 2.646a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 0 1 .708 0z"/>
                    <path fill-rule="evenodd" d="M13.5 2.5a.5.5 0 0 1 .5.5v4a2.5 2.5 0 0 1-2.5 2.5H3a.5.5 0 0 1 0-1h8.5A1.5 1.5 0 0 0 13 7V3a.5.5 0 0 1 .5-.5z"/>
                </svg>
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col">
            <table class="table table-striped table-dark table-hover">
                <tr>
                    <td style="width: 20%">Priority:</td>
                    <td style="width: 80%">{{ $book->priority ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Read:</td>
                    <td>{{ $book->read ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <td>Title:</td>
                    <td>{{ $book->title }}</td>
                </tr>
                <tr>
                    <td>Author:</td>
                    <td>{{ $book->author->name }}</td>
                </tr>
                <tr>
                    <td>ISBN-10:</td>
                    <td>{{ $book->isbn_10 }}</td>
                </tr>
                <tr>
                    <td>ISBN-13:</td>
                    <td>{{ $book->isbn_13 }}</td>
                </tr>
                <tr>
                    <td>Publish Date:</td>
                    <td>{{ $book->published_at->toDateString() }}</td>
                </tr>
                <tr>
                    <td>Summary:</td>
                    <td>{{ $book->summary }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
