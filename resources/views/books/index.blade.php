@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <h2>{{ __('My Books') }}</h2>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{ url('/books/create') }}" class="btn btn-primary">
                        New Book
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8 3.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5H4a.5.5 0 0 1 0-1h3.5V4a.5.5 0 0 1 .5-.5z"/>
                            <path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0V8z"/>
                            <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <book-collection
                :user="{{ auth()->user()->makeVisible('api_token')->toJson() }}"
                :books="{{ $books->toJson() }}"
                v-cloak
            ><book-collection>
        </div>
    </div>
</div>
@endsection
