<?php

namespace App\Services;

use Log;
use Http;
use Throwable;

class GoogleBooksApiService
{
    public const MAX_RESULTS = 10;
    private $url;

    public function __construct() {
        $this->url = env('GOOGLE_BOOKS_URL') . '/volumes';
    }

    /**
     * Search for books by title
     *
     * @param  string $title
     * @return Illuminate\Support\Collection
     */
    public static function searchByTitle($title)
    {
        $self = new static();
        try {
            $response = Http::get($self->url, [
                'q'          => 'intitle:' . $title,
                'maxResults' => self::MAX_RESULTS
            ]);
        } catch(Throwable $e) {
            Log::error($e);
            return collect();
        }

        if ($response->failed()) {
            Log::error($response);
            return collect();
        }

        if (! isset($response['items'])) {
            return collect();
        }

        return collect($response['items'])->map(function($item) use ($self) {
            return $self->deserializeItem($item);
        });
    }

    /**
     * Search for a book by volume id
     *
     * @param  string $id
     * @return array
     */
    public static function searchByVolumeId($id)
    {
        $self = new static();
        try {
            $response = Http::get($self->url . '/' . $id);
        } catch(Throwable $e) {
            Log::error($e);
            return null;
        }

        if ($response->failed()) {
            Log::error($response);
            return null;
        }

        return $self->deserializeItem($response->json());
    }

    private function deserializeItem(array $item)
    {
        $info = $item['volumeInfo'];
        return [
            'id'             => $item['id'],
            'title'          => $info['title'],
            'subtitle'       => $info['subtitle'] ?? null,
            'author'         => implode(', ', $info['authors']),
            'publisher'      => $info['publisher'] ?? null,
            'published_at'   => $info['publishedDate'] ?? null,
            'description'    => $info['description'] ?? null,
            'isbn_10'        => $this->getIdentifier($info, 'isbn_10'),
            'isbn_13'        => $this->getIdentifier($info, 'isbn_13'),
            'page_count'     => $info['pageCount'] ?? null,
            'categories'     => $info['categories'] ?? null,
            'thumbnail'      => $info['imageLinks']['thumbnail'] ?? null
        ];
    }

    private function getIdentifier(array $info, $identifer)
    {
        if (! isset($info['industryIdentifiers'])) {
            return null;
        }

        $identifers = $info['industryIdentifiers'];
         $match = array_filter($identifers, function($value) use ($identifer) {
            return strtoupper($identifer) == $value['type'];
        });
        return empty($match) ? null : array_values($match)[0]['identifier'];
    }
}
